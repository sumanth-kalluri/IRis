'''
 * Project          : IRis
 * Purpose          : HackFest 2018 - The Annual 36 Hour Hackathon At IIT (ISM) Dhanbad
 * Module           : Master Hub (RasPi) [IR Communication Module]
 ---------------------------------------------------------------------------------------
 * Platform         : Raspberry Pi 3B [running Raspbian 9 (Stretch)]
 * Serial Device    : Arduino Uno R3 (via USB)
 ---------------------------------------------------------------------------------------
 * Author           : Nishad Mandlik
 * Organization     : RoboISM - The Robotics Club Of IIT (ISM) Dhanbad
 * 
'''

def stop(ID,serDev):
    #Write Input Stop Command To Arduino
    serDev.write("*0," + str(ID) + "#")

def init(ID,serDev):
    #Write Initiate Command To Arduino
    serDev.write("*1," + str(ID) + "#")

    #Start When Data Start Character Is Encountered
    while serDev.read(1) != '*' :
        continue
    
    if serDev.read(1) != '1' :
        print ("Failed To Initialize")
        return
    
    
    #Read Separation Character After Command Code
    serDev.read(1)
    
    #First Element Is Number Of Active Devices
    active = [0]
    
    for i in range (0,8) :
        stat = ord(serDev.read(1))
        for j in range (0,8) :
            #Check The Status
            if stat & 0b00000001 :
                #Add The ID To Active Devices List
                active.append(8*i + j)

                #Increment The Number Of Active Devices
                active[0] = active[0] + 1

            #Bring Status Of Next ID Into LSB (Check Position)    
            stat = stat>>1

        #Read The Separation Or End Character After Every Byte
        serDev.read(1)

    return active        

        

def enquire(ID,qNo,serDev,answers,qs):
    #Write Enquire Command To Arduino
    serDev.write("*2," + str(ID) + "#")
    while serDev.read(1) != '*' :
        continue

    #Start When Data Start Character Is Encountered
    if serDev.read(1) != '2' :
        print ("Failed To Enquire")
        return
    #Read Separation Character After Command Code
    serDev.read(1)

    roll = ''

    while True :
        nextChar = serDev.read(1)

        #If End Character Is Encountered, Return Back
        if nextChar == '#' :
            return

        #On Roll Nnumber End, Read The Option Entered And Update The Responses List 
        if nextChar == '/':
            opt = serDev.read(1)

            if opt>=65 and opt<=68 :
                #Store The Response In The Cell Corresponding To The Appropriate Questiion And Roll Number
                answers[int(roll)][qNo*2 - 1] = opt

                #Store The Error Code in The Cell Beside
                answers[int(roll)][qNo*2] = qs[qNo][2*ord(opt) - 128]
            
            #Reset The Roll Number Variable To Accept New Value
            roll = ''

        #If Character Is Digit, Then Update Then Use It As Roll Number
        elif nextChar>='0' and nextChar<='9' :
            roll = roll + nextChar
        
    

def reset(ID,serDev):
    #Write Reset Input Command To Arduino
    serDev.write("*3," + str(ID) + "#")
    
