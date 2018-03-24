/*
 * Project          : IRis
 * Purpose          : HackFest 2018 - The Annual 36 Hour Hackathon At IIT (ISM) Dhanbad
 * Module           : Master IR Interface (Arduino)
 ---------------------------------------------------------------------------------------
 * Board            : Arduino Uno R3
 * IR Demodulator   : VS1838B
 * Library          : IRremote (For IR Communication with Arduino Series) [https://github.com/z3t0/Arduino-IRremote]
 * IDE              : Arduino IDE 1.8.4 On Ubuntu 16.04 LTS
 ---------------------------------------------------------------------------------------
 * Author           : Nishad Mandlik
 * Organization     : RoboISM - The Robotics Club Of IIT (ISM) Dhanbad
 * 
*/

//Digital Pin 3   : IR Tx
//Digital Pin 11  : IR Rx

#include <IRremote.h>
#define UID 0

#define STOP_CODE 0
#define INIT_CODE 1
#define ENQUIRE_CODE 2
#define RESET_CODE 3

uint16_t usrIp = 0;

//Array To Store Bitwise Availability Status Of All Slaves.
uint8_t slaveAvail[8] = {0,0,0,0,0,0,0,0};

uint16_t irOut[] = {5000, 2500, 750, 750, 750, 750, 750, 750, 750, 750, 750, 750, 750, 750, 750, 750, 750, 750, 500};
byte decodeStat = 1;

//Array To Store The UIDs Of Slaves Which Are Available.
//0th Element Stores Number Of Such Active Slaves
uint8_t UIDActive[64];


IRsend irtx; //Library Object to Send IR Signals.
IRrecv irrx(2); //Library Objet to Receive IR Signals.

decode_results irIn;

//Structure To Store Data Decoded From The IR Signal.
struct irCmd {
  //According To The IR Protocol Used, Information Is Received Bytewise.
  //The 6 MSBs Contain Address Of Transmitting Slave.
  //The 2 LSBs Denote User-Input Of Slave.
  uint8_t address: 6;
  uint8_t option: 2;
}irResponse;

//Function To Convert IR Signal To Byte Data
uint8_t decodeRaw() {
  //Signal Is Present In Ticks Of 50uS.

  //First HIGH Pulse : 5000 (3500 to 6500)
  if (irIn.rawbuf[1]<70 && irIn.rawbuf[1]>130) {
    return 1;
  }
  
  //First LOW Pulse : 2500 (1500 to 3500)
  if (irIn.rawbuf[2]<30 && irIn.rawbuf[2]>70) {
    return 2;
  }

  //End HIGH Pulse : 500 (250 To 750)
  if (irIn.rawbuf[19]<5 && irIn.rawbuf[19]>15) {
    return 3;
  }

  //First 6 bits (Address)
  for (uint8_t i=3; i<15; i+=2) {
    irResponse.address = irResponse.address<<1;
    if (irIn.rawbuf[i]>15) {
      //HIGH Pulse : 1000 (>750)
      irResponse.address = irResponse.address | 0b00000001;
    }
    else if (irIn.rawbuf[i]<15) {
      //LOW Pulse : 500 (<750)
      irResponse.address = irResponse.address & 0b11111110;
    }
    else {
      return 4;
    }
  }

  //Last 2 bits (Instruction)
  for (uint8_t i=15; i<19; i+=2) {      
    irResponse.option = irResponse.option<<1;
    if (irIn.rawbuf[i]>17) {
      //HIGH Pulse : 1000 (>850)
      irResponse.option = irResponse.option | 0b00000001;
    }
    else if (irIn.rawbuf[i]<13) {
      //LOW Pulse : 500 (<650)
      irResponse.option = irResponse.option & 0b11111110;
    }
    else {
      return 5;
    }
  }
  return 0;
}

//Encode A Byte Of Data Into Pulse Width Modulated Form
void encodeRaw(uint8_t adr, uint8_t opt) {
  for (byte i = 6; i>=1; i--)
  {
    if ((adr>>(6-i)) & 0b1) {
      //When bit is 1
      irOut[2*i] = 1000;      //HIGH Time (in uS)
      irOut[2*i + 1] = 500;   //LOW Time (in uS)
    }
    else {
      //When bit is 0
      irOut[2*i] = 500;       //HIGH Time (in uS)
      irOut[2*i + 1] = 1000;  //LOW Time (in uS)
    } 
  }
  for (byte i = 2; i>=1; i--)
  {
    if ((opt>>(2-i)) & 0b1) {
      //When bit is 1
      irOut[2*i + 12] = 1000; //HIGH Time (in uS)
      irOut[2*i + 13] = 500;  //LOW Time (in uS)
    }
    else {
      //When bit is 0
      irOut[2*i + 12] = 500;  //HIGH Time (in uS)
      irOut[2*i + 13] = 1000; //LOW Time (in uS)
    } 
  }
  return;
}

uint8_t slaveResponse(uint8_t slaveID, uint8_t slaveOp) {
  //If Slave Responds Properly, This Function Will Return 1 or 2, Else It Will Return 0.
  encodeRaw(slaveID,slaveOp);
  uint8_t temp=0;    
  irtx.sendRaw(irOut, sizeof(irOut) / sizeof(irOut[0]), 38);
  irrx.enableIRIn();
  while (!irrx.decode(&irIn)) {
    temp+=1;
    delay(1);
    if (temp > 50) {
      return 0;
    }
  }  
  decodeStat = decodeRaw();
  if (irResponse.address == slaveID) {
    //For Options A, B, C, D
    if (decodeStat == 0) {
      return 1;
    }

    //For No Option Entered
    else if (decodeStat == 5) {
      return 2;        
    }
  }
}

uint8_t initSlaves(uint8_t ID) {
  //If ID Is Non-Zero, Only The Slave Corresponding To That ID Will Be Initialized.
  //If ID is Zero, All Slaves Will Be Initialized.

  //After Initialization, Availability Status Of All (63) Slaves Will Be Updated In "slaveAvail" Array.
  //LSB Of 0th Element of "slaveAvail" Is Reserved For Master.
  //The Rest 63 Bits (7 Bits Of 0th Element & 8 Bits Each Of All Other Elements) Store The Availability Status Of 63 Slaves.

  Serial.print('*');
  Serial.print(INIT_CODE);
  Serial.print(':');
  
  if (ID) {
    //Transmit The Status To The Serial Host.
    Serial.print(ID);
    Serial.print('/');
    if (slaveResponse(ID,1)) {
      Serial.println("T#");
      return 1;
    }
    Serial.println("F#");
    return 2;
  }

  //Initially Set The Number Of Active Slaves To Zero.
  UIDActive[0] = 0;
  
  //For 0th Device (Master)
  slaveAvail[0] = 0b10000000;

  //For All Slaves
  for (uint8_t i = 1; i<64; i++) {
    //Insert Availability Status From MSB And Shift If Leftwards One By One.
    slaveAvail[i/8] = slaveAvail[i/8]>>1;
    if (slaveResponse(i,1)) {
      slaveAvail[i/8] = slaveAvail[i/8] | 0b10000000; //Make The MSB = 1, And Keep Rest Bits Unchanged.
      UIDActive[0] = UIDActive[0] + 1; //Increase The Number Of Active Slaves By 1.
      UIDActive[UIDActive[0]] = i; //Append The Slave UID To The List Of Active Slave.
    }
  }

  //Transmit The Status To The Serial Host
  for (uint8_t i = 0; i<7; i++) {
    Serial.print((char)slaveAvail[i]);
    Serial.print(',');
  }
  Serial.print((char)slaveAvail[7]);
  Serial.println('#');
  return 0;
}

uint8_t askSlaves(uint8_t ID) {
  Serial.print('*');
  Serial.print(ENQUIRE_CODE);
  Serial.print(':');
  
  //If ID Is Non-Zero, Only The Slave Corresponding To That ID Will Be Enquired.
  //If ID is Zero, All Slaves Will Be Enquired.
  if (ID) {
    //Transmit The Inputs Recorded By Slave To The Serial Host
    if (slaveResponse(ID,2)) {
      Serial.print(ID);
      Serial.print('/');
      Serial.print(irResponse.option);
      Serial.println('#');
      return 1;
    }
  }

  //Array To Store Options Recorded By Available Slaves.
  uint8_t slaveOpts[UIDActive[0]+1];

  //Run The Loop For All Active Slaves
  for (uint8_t i = 1; i<=UIDActive[0]; i++) {
    uint8_t resp = slaveResponse(UIDActive[i],2);
    if (resp==1) {
      //Convert 0-3 To 65-68 (ASCII Values Of A-D)
      slaveOpts[i] = irResponse.option + 65;
    }
    else if (resp==2) {
      slaveOpts[i] = 78;
    }
  }

  //Transmit The Inputs Recorded By Slaves To The Serial Host.
  for (uint8_t i = 1; i<UIDActive[0]; i++) {
    Serial.print(UIDActive[i]);
    Serial.print('/');
    Serial.print((char)slaveOpts[i]);
    Serial.print(',');
  }
  Serial.print(UIDActive[UIDActive[0]]);
  Serial.print('/');
  Serial.print((char)slaveOpts[UIDActive[0]]);
  Serial.println('#'); 
}

void setup() {
  Serial.begin(115200);
  Serial.println("Start");
  encodeRaw(UID,2);
  irrx.enableIRIn();
  Serial.println("Enabled\n");
}

void loop() {
  //Send The Encoded Signal
  if (!Serial.available()) {
    return;
  }
  if (Serial.read()!='*') {
    return;
  }
  uint8_t opCode = Serial.parseInt();
  uint8_t arg = Serial.parseInt();
  if (opCode == 0 || opCode == 3) {
    encodeRaw(arg,opCode);  
    irtx.sendRaw(irOut, sizeof(irOut) / sizeof(irOut[0]), 38);
    irrx.enableIRIn();
  }
  else if (opCode == 1) {
    initSlaves(arg);
  }
  else if (opCode == 2) {
    askSlaves(arg);
  }
  else {
    Serial.print('*');
    Serial.print(opCode);
    Serial.println(":Z#");
  }
  irrx.resume();
}
