#include "MyMutex.h"

CMyMutex::CMyMutex(char* p)
{
	m_mutex=CreateMutex(NULL,TRUE,(LPCWSTR)p);
}

CMyMutex::~CMyMutex(void)
{
}
