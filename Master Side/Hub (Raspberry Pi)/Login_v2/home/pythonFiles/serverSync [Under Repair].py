'''
 * Project          : IRis
 * Purpose          : HackFest 2018 - The Annual 36 Hour Hackathon At IIT (ISM) Dhanbad
 * Module           : Master Hub (RasPi) [Server Synchronization Module]
 ---------------------------------------------------------------------------------------
 * Platform         : Raspberry Pi 3B [running Raspbian 9 (Stretch)]
 * Serial Device    : Arduino Uno R3 (via USB)
 ---------------------------------------------------------------------------------------
 * Author           : Nishad Mandlik
 * Organization     : RoboISM - The Robotics Club Of IIT (ISM) Dhanbad
 * 
'''


import socket
import ftplib
import os
import pyinotify
from threading import Thread
from time import sleep

#Function To Upload UnSynced Responses
def respUp() :
    while True :
        
        #Obtain A List Of Local Response Files
        respOffline = os.listdir("../Responses")

        while True :
            try:
                #Find The Host From Its Name Through DNS
                fileDest = socket.gethostbyname(fileServer)

                #Create Connection To Port 21 Of FTP Server [Timeout = 10sec]
                servConn = socket.create_connection((fileDest,21),10)

                #If Connection Is Estblished, Break Out Of Loop To Proceed With Data Upload
                break
            except:
                #If Connection Cannot Be Established, Try Again After 50sec
                sleep(50)
                continue

        #Initiate A Session With The Sync Server
        syncUp = ftplib.FTP(fileServer, servUser, servPass)
        
        #Change The Directory To Responses
        syncUp.cwd("/Responses")

        #Obtain A List Of Response Files In The Online Directory Excluding The Present Directory(.) And Parent Directory (..)
        respOnline = syncUp.nlst()[2:]

        #Upload Unsynced Responses To Server
        for respFile in respOffline :
            if respFile in respOnline :
                continue
            else :
                syncResp = open("../Responses/" + respFile, "rb")
                syncUp.storbinary("STOR " + respFile, syncResp)
                syncResp.close()

        syncUp.quit()

        #Proceed For Next Sync When New File Is Created
        while not respNotif.check_events() :
            sleep(60)
            continue

        #Read And Process The File Creation Events To Clear Them From The Notifier Queue
        respNotif.read_events()
        respNotif.process_events()

        

#Function To Download UnSynced Question Sets
def quesDown() :
    while True :
        
        #Obtain A List Of Local Question Sets
        quesOffline = os.listdir("../Question Sets")

        while True :
            try:
                #Find The Host From Its Name Through DNS
                fileDest = socket.gethostbyname(fileServer)

                #Create Connection To Port 21 Of FTP Server [Timeout = 10sec]
                servConn = socket.create_connection((fileDest,21),10)

                #If Connection Is Estblished, Break Out Of Loop To Proceed With Data Upload
                break
            except:
                #If Connection Cannot Be Established, Try Again After 50sec
                sleep(50)
                continue

        #Initiate A Session With The Sync Server
        syncDown = ftplib.FTP(fileServer, servUser, servPass)

        #Change The Directory To Question Sets
        syncDown.cwd("/Question Sets")

        #Obtain A List Of Question Sets In The Online Directory Excluding The Present Directory(.) And Parent Directory (..)
        quesOnline = syncDown.nlst()
        
        #Obtain Unsynced Question Sets From Server
        for quesFile in quesOnline :
            if quesFile in quesOffline :
                continue
            else :
                syncQues = open("../Question Sets/" + quesFile, "wb")
                syncDown.retrbinary('RETR ' + quesFile, syncQues.write)
                syncQues.close()                
        
        syncDown.quit()

        #Check For New Question Sets After 10 Mins
        sleep(600)

    

#Set The FTP Host Name, Username And Password
fileServer = "f19-preview.awardspace.net"
servUser = "2665662"
servPass = "ProjectIRis2018"

#Create A WatchDog To Monitor File Events
watchdog = pyinotify.WatchManager()

#Configure The WatchDog To Monitor File Creation Events In Responses Directory
watchdog.add_watch("../Responses", pyinotify.IN_CREATE)

#Start The Notifier
respNotif = pyinotify.Notifier(watchdog,timeout=10)

threadUp = Thread(target = respUp)
threadDown = Thread(target = quesDown)
threadUp.start()
threadDown.start()
    
