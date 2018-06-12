'''
 * Project          : IRis
 * Purpose          : Jharkhand Government Project, Ranchi
 * Module           : Master Hub (RasPi) [Main Module]
 ---------------------------------------------------------------------------------------
 * Platform         : Raspberry Pi 3B [running Raspbian 9 (Stretch)]
 * Serial Device    : Arduino Uno R3 (via USB)
 ---------------------------------------------------------------------------------------
 * Author           : Nishad Mandlik
 * Organization     : WhizMantra Educational Services
 * 
'''

import os
from time import sleep

DISP = 0
os.chdir('/var/www/html/Login_v2/home/pythonFiles/')

runStat = os.listdir('./')
if 'running' in runStat :
        print "testDetect Already Running"
        exit()

os.system("touch running")

#Directory To Monitor The Test File 
testDir = '/var/www/html/Login_v2/home/currentTest/'



while True :
        testList = os.listdir(testDir)
        if len(testList)==1 and testList[0][len(testList[0])-3:] == "csv" :                
                os.system("DISPLAY=:" +  str(DISP) + " /usr/bin/python ./testStart.py")
                print "Test Started"
                pass
        elif len(testList)==0 :
                pass
        else:                
                os.system("sudo rm -rf " + testDir + "*")
                os.system("touch /home/pi/Desktop/Detect")
        sleep(0.5)
        
        
        
    

