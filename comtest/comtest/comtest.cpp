// comtest.cpp : 定义控制台应用程序的入口点。
//

#include "stdafx.h" 
#include <stdio.h>
#include <list>
#include <winsock2.h>
#include "MyMutex.h"

#pragma comment(lib, "ws2_32.lib")
//函数原型
void init_com(void);       //定义串口初始化函数 
int init_socket();
void char2hex(unsigned char*p, int len);
DWORD WINAPI RcvFromCom(LPVOID lParam); //定义子线程函数
DWORD WINAPI SendToCom(LPVOID lParam); //定义子线程函数

#define UART_LEN 256
#define MYCOM "com6"

//结构定义
struct Thread_Data        //定义一个结构类型，用于传参数给子线程 
{ 
	HANDLE hcom; 
	OVERLAPPED vcc; 
};
struct UartData
{
	UartData(char* _p, int _len)
	{
		len = _len;
		memset(p, 0, UART_LEN);
		memcpy(p, _p, len);
	}
	char p[UART_LEN];
	int len;
};
std::list<UartData> g_SendMsgList;
std::list<UartData> g_ReceiveMsgList;

class RcvBuf
{
public:
	RcvBuf()
	{
		clear();
	}
	void writeInReceiveBuf(char* p, int len)
	{	
		//if (!checkData())
		//{
		//	printf("head error!");
		//}
		memcpy(buf+end, p, len); 
		end += len;
		if (checkBuf())
		{
			//sendtocom
			printf("receive data from com len: %d\n",  dataLen);
			char2hex(buf+start, dataLen);
			g_ReceiveMsgList.push_back(UartData((char*)buf+start, dataLen));
			printf("g_ReceiveMsgList.size[%d]\n", g_ReceiveMsgList.size());
			clear();
		}
		
	}
private:
	void clear()
	{
		memset(buf, 0, UART_LEN);
		start=0;
		end=0;
		dataLen=0;
	}
	void checkData();
	void findHead()
	{
		bool find=false;
		for (int i=0;i<end;i++)
		{
			if (buf[i] == 0xf4)
			{
				find=true;
				start=i;
				if (i+1<end && buf[i+1]==0xf5)
				{
					if (i+3<end)
					{
						dataLen=buf[i+2]*256+buf[i+3]+4;
						return;
					}
				}
			}
		}
		if (!find)
			clear();
	}
	bool checkBuf()
	{
		findHead();
		if (dataLen!=0)
		{
			if (dataLen <= end-start)
			{
				return true;
			}
		}
		return false;
	}
	void move()
	{
		end=end-dataLen;
		memcpy(buf, buf+dataLen, end);
		memset(buf+end, 0, UART_LEN-end);
		start=0;
		dataLen=0;
	}
	

	unsigned char buf[UART_LEN];
	int start;
	int end;
	int dataLen;
};
RcvBuf g_RcvBuf;


//变量定义
unsigned char receive_data[UART_LEN];     //定义接收数据缓存 
HANDLE m_hCom = NULL;       //定义一个句柄，用于打开串口 
CRITICAL_SECTION com_cs;      //定义临界变量对象
CRITICAL_SECTION com_cs2;      //定义临界变量对象

HANDLE mutexSend;
HANDLE mutexReceive;


//=======================================================================================
int main(int argc, char* argv[]) 
{ 
	printf("******************控制台调试工具*********************\n");

	InitializeCriticalSection(&com_cs);   //初始化临界变量
	InitializeCriticalSection(&com_cs2);   //初始化临界变量
	init_com(); //初始化串口 
	init_socket();
	//while(1) 
	//{ 
	//	Sleep(200);//使主线程挂起 
	//}
	// CloseHandle(m_hCom);
	printf("\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n");
	printf("******************控制台调试工具*********************\n");
	getchar();
	return 0; 
}
void char2hex(unsigned char* p, int len)
{
	for (int i=0;i<len;i++)
	{
		printf("%#x ", p[i]);
	}
	printf("\n");
}
int init_socket()
{
	WSADATA wsaData;
	WORD sockVersion = MAKEWORD(2,2);
	if(WSAStartup(sockVersion, &wsaData) != 0)
	{
		return 0;
	}

	SOCKET serSocket = socket(AF_INET, SOCK_DGRAM, IPPROTO_UDP); 
	if(serSocket == INVALID_SOCKET)
	{
		printf("socket error !");
		return 0;
	}

	sockaddr_in serAddr;
	serAddr.sin_family = AF_INET;
	serAddr.sin_port = htons(8888);
	serAddr.sin_addr.S_un.S_addr = INADDR_ANY;
	if(bind(serSocket, (sockaddr *)&serAddr, sizeof(serAddr)) == SOCKET_ERROR)
	{
		printf("bind error !");
		closesocket(serSocket);
		return 0;
	}

	sockaddr_in remoteAddr;
	int nAddrLen = sizeof(remoteAddr); 
	while (true)
	{
		char recvData[UART_LEN];  
		int ret = recvfrom(serSocket, recvData, UART_LEN, 0, (sockaddr *)&remoteAddr, &nAddrLen);
		if (ret < 0)
		{
			continue;
		}

		//char *p = new char[UART_LEN];
		//memset(p, 0, UART_LEN);
		//memcpy(p, recvData, ret);
		if (ret>8)
		{
			//send to com
			g_SendMsgList.push_back(UartData(recvData,ret));
		}

		if (ret > 8)
		{
			recvData[ret] = 0x00;
			printf("reveive data from: %s len:%d\r\n", inet_ntoa(remoteAddr.sin_addr), ret);
			char2hex((unsigned char*)recvData, ret);            
		}
		static int aa=0;
		char sendData[UART_LEN]={0};
		sprintf_s(sendData, "my name is c udp server!%d\n", aa++ );
		Sleep(50);
		//sendto(serSocket, sendData, (int)strlen(sendData), 0, (sockaddr *)&remoteAddr, nAddrLen);
		if (g_ReceiveMsgList.size()>0)
		{
			UartData seData = g_ReceiveMsgList.front();
			char pData[UART_LEN]= {0};
			int datalen=seData.len;
			memcpy(pData, seData.p, seData.len);
			g_ReceiveMsgList.pop_front();
			printf("g_ReceiveMsgList.size[%d]-->[%d]\n", g_ReceiveMsgList.size()+1, g_ReceiveMsgList.size());
			sendto(serSocket, pData, datalen, 0, (sockaddr *)&remoteAddr, nAddrLen);
		}
		else
		{
			sendto(serSocket, "null", (int)strlen("null"), 0, (sockaddr *)&remoteAddr, nAddrLen);
		}
		
	}
	closesocket(serSocket); 
	WSACleanup();
	return 0;
}

void init_com() 
{
	EnterCriticalSection(&com_cs);//进入临界段 
	HANDLE hThread1 = NULL;//子线程句柄
	HANDLE hThread2 = NULL;//子线程句柄
	COMMTIMEOUTS TimeOuts;//定义超时结构 
	DCB dcb; //定义串口设备控制结构 
	OVERLAPPED wrOverlapped;//定义异步结构
	OVERLAPPED wrOverlapped2;//定义异步结构
	static struct Thread_Data thread_data;//定义一个结构对象，用于给子线程传递参数
	static struct Thread_Data thread_data2;//定义一个结构对象，用于给子线程传递参数

	//1. 打开串口文件
	printf("iput com number: \n");
	char chr[64]={0};
	gets_s(chr, 64);
	int com=atoi(chr);
	if (com<1||com>100)
	{
		printf("warning! com[%d] is error!\n", com);
	}
	sprintf_s(chr, 64, "\\\\.\\com%d", com);
	//printf("%d\n", chr);
	m_hCom = CreateFileA((chr),GENERIC_READ | GENERIC_WRITE, 0, NULL, OPEN_EXISTING, FILE_FLAG_OVERLAPPED, NULL); 
	if (m_hCom == INVALID_HANDLE_VALUE) 
	{
		printf("CreateFile fail\n"); 
	} 
	else 
	{
		printf("CreateFile ok\n"); 
	}

	//2. 设置读写缓冲区 
	if(SetupComm(m_hCom,2048,2048)) 
	{
		printf("buffer ok\n"); 
	} 
	else 
	{
		printf("buffer fail\n"); 
	} 

	//3. 设置串口操作超时 
	//memset(&TimeOuts,0,sizeof(TimeOuts)); 
	TimeOuts.ReadIntervalTimeout         = 1000; 
	TimeOuts.ReadTotalTimeoutConstant    = 1000; 
	TimeOuts.ReadTotalTimeoutMultiplier = 1000; 
	TimeOuts.WriteTotalTimeoutConstant   = 1000; 
	TimeOuts.WriteTotalTimeoutMultiplier = 1000; 
	if(SetCommTimeouts(m_hCom,&TimeOuts)) 
	{
		printf("comm time_out ok!\n");
	}

	//4. 设置串口参数 
	if (GetCommState(m_hCom,&dcb)) 
	{
		printf("getcommstate ok\n"); 
	} 
	else 
	{
		printf("getcommstate fail\n"); 
	} 
	dcb.DCBlength = sizeof(dcb); 
	if (BuildCommDCB(_T("115200,n,8,1"),&dcb))//填充ＤＣＢ的数据传输率、奇偶校验类型、数据位、停止位 
	{
		printf("buildcommdcb ok\n"); 
	} 
	else 
	{
		printf("buildcommdcb fail\n"); 
	}
	if(SetCommState(m_hCom,&dcb)) 
	{ 
		printf("setcommstate ok\n"); 
	} 
	else 
	{ 
		printf("setcommstate fail\n"); 
	}

	//5.初始化异步结构 
	ZeroMemory(&wrOverlapped,sizeof(wrOverlapped)); 
	wrOverlapped.Offset = 0; 
	wrOverlapped.OffsetHigh = 0;
	ZeroMemory(&wrOverlapped2,sizeof(wrOverlapped2)); 
	wrOverlapped2.Offset = 0; 
	wrOverlapped2.OffsetHigh = 0;

	if (wrOverlapped.hEvent != NULL) 
	{ 
		ResetEvent(wrOverlapped.hEvent); 
	} 
	if (wrOverlapped2.hEvent != NULL) 
	{ 
		ResetEvent(wrOverlapped2.hEvent); 
	} 
	wrOverlapped.hEvent = CreateEvent(NULL,TRUE,FALSE,NULL);//创建手工复位事件
	wrOverlapped2.hEvent = CreateEvent(NULL,TRUE,FALSE,NULL);//创建手工复位事件

	//创建一个线程
	thread_data.vcc = wrOverlapped; ;
	thread_data.hcom = m_hCom;
	thread_data2.vcc = wrOverlapped2; 
	thread_data2.hcom = m_hCom;
	hThread1=CreateThread(NULL,0,RcvFromCom,(LPVOID)&thread_data,0,NULL); //『注意』创建线程
	hThread2=CreateThread(NULL,0,SendToCom,(LPVOID)&thread_data2,0,NULL); //『注意』创建线程

	LeaveCriticalSection(&com_cs);//推出临界段 
}

DWORD WINAPI RcvFromCom(LPVOID lParam) //线程的执行函数 
{

	Thread_Data* pThread_Data = (Thread_Data*)lParam; //将线程函数参数给新定义的结构指针 
	HANDLE com= pThread_Data->hcom;       //通过新定义的结构指针使用原结构的成员 
	OVERLAPPED wrOverlapped1 = pThread_Data->vcc;   //通过新定义的结构指针使用原结构的成员 
	COMSTAT ComStat ;          //定义一个串口状态结构对象，给结构有10个参数，第九个参数 
	//DWORD cbInQue;          //表示出入缓冲区有多少字节的数据到达， 
	DWORD word_count;          //存放实际读到的数据字节数存放 
	DWORD Event = 0; 
	DWORD CommEvtMask=0 ; 
	DWORD result; 
	DWORD error; 

	SetCommMask(com,EV_RXCHAR);//设置串口等待事件

	while(1) 
	{ 
		result = WaitCommEvent(com,&CommEvtMask,&wrOverlapped1);//等待事件函数 
		if(!result) 
		{ 
			switch(error = GetLastError()) 
			{ 
			case ERROR_IO_PENDING : 
				break; 
			case 87: 
				break; 
			default: 
				break; 
			} 
		} 
		else 
		{ 
			result = ClearCommError(com,&error,&ComStat); 
			if(ComStat.cbInQue == 0) 
			{ 
				continue; 
			} 
		}
		Event = WaitForSingleObject(wrOverlapped1.hEvent,INFINITE);//异步结果等待函数 
		EnterCriticalSection(&com_cs); 
		result = ClearCommError(com,&error,&ComStat); 
		if(ComStat.cbInQue == 0) 
		{ 
			continue; 
		}

		ReadFile(com,receive_data,ComStat.cbInQue,&word_count,&wrOverlapped1);//从系统接收缓冲区读出接收到得所有数据 
		if (ComStat.cbInQue>UART_LEN)
			continue;
		g_RcvBuf.writeInReceiveBuf((char*)receive_data, ComStat.cbInQue);
		LeaveCriticalSection(&com_cs);//推出临界段 
		//char2hex(receive_data, 15);
		if (5>8)
		{
			//send to com
			//g_SendMsgList.push_back(p);
		}
		for(int k=0;k<UART_LEN;k++) 
		{ 
			receive_data[k] = 0x00; 
		} 
	} 
	return 0; 
}

DWORD WINAPI SendToCom(LPVOID lParam) //线程的执行函数 
{

	Thread_Data* pThread_Data = (Thread_Data*)lParam; //将线程函数参数给新定义的结构指针 
	HANDLE com= pThread_Data->hcom;       //通过新定义的结构指针使用原结构的成员 
	OVERLAPPED wrOverlapped1 = pThread_Data->vcc;   //通过新定义的结构指针使用原结构的成员 
	//COMSTAT ComStat ;          //定义一个串口状态结构对象，给结构有10个参数，第九个参数 
	//DWORD cbInQue;          //表示出入缓冲区有多少字节的数据到达， 
	DWORD word_count=0;          //存放实际读到的数据字节数存放 
	DWORD Event = 0; 
	DWORD CommEvtMask=0 ; 

	while(1) 
	{ 	
		if (g_SendMsgList.size()==0)
		{
			continue;
		}
		UartData seData = g_SendMsgList.front();
		char pData[UART_LEN]= {0};
		int datalen=seData.len;
		memcpy(pData, seData.p, seData.len);
		g_SendMsgList.pop_front();

		EnterCriticalSection(&com_cs2); 
		//result = ClearCommError(com,&error,&ComStat); 
		/*if(ComStat.cbInQue == 0) 
		{ 
			continue; 
		}*/
		//int dddd=ComStat.cbInQue;
		WriteFile(com,pData,datalen,&word_count,&wrOverlapped1);
		//ReadFile(com,receive_data,ComStat.cbInQue,&word_count,&wrOverlapped1);//从系统接收缓冲区读出接收到得所有数据 
		LeaveCriticalSection(&com_cs2);//推出临界段 
		
	} 
	return 0; 
}

