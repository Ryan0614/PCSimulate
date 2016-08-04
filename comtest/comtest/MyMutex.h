#pragma once
#include <windows.h>
#include <iostream>

class CMyMutex
{
public:
	CMyMutex(char* p);
public:
	virtual ~CMyMutex(void);
private:
	HANDLE m_mutex;
};

