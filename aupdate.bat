copy ..\dgate\dgate.exe .\install32
copy ..\dgate\dgate64.exe .\install64
copy ..\servertask\cqdicom.dll .
copy ..\dgateserv\dgateserv.exe .
copy ..\gui\win32\conquestdicomserver.exe .
copy ..\doc\windowsmanual.pdf .
copy ..\doc\DicomConformance_FilesLST_Changes.pdf .
copy ..\doc\linuxmanual.pdf .

copy dgate.dic .\linux
c:\dicom150beta\dgate64 --mk_binary_dic:t.dic
copy c:\dicom150beta\t.dic .\webserver\cgi-bin\dgate.dic
copy c:\dicom150beta\t.dic .\webserver\cgi-bin\newweb\dgate.dic

del .\webserver\cgi-bin\dgate.exe
del .\webserver\cgi-bin\newweb\dgate.exe
