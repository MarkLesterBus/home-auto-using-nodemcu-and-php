#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <Wire.h> 
#include <LiquidCrystal_I2C.h>
#include <EmonLib.h>

//WIFI SSID AND PASSWORD
//Access point credentials

const char* serverIP="192.168.1.7";
const char* ssid = "2.4 Glasswire";
const char* password = "m4rkl3st3r";

//LIGHTS AND OUTLET PINS
int outletPin = 12;
int lightPin = 13;
int outlet = LOW;
int light = LOW;
boolean calibration = false;
boolean posted = false;

double outletPower, outletIrms;
double lightPower, lightIrms;

WiFiServer server(80);
EnergyMonitor emon1;
EnergyMonitor emon2;
LiquidCrystal_I2C lcd(0x27,20,4);
 
void setup() {
  IPAddress ip(192, 168, 1, 20);
  IPAddress gateway(192, 168, 1, 1);
  IPAddress subnet(255, 255, 255, 0);
  
  //Set a static IP
  WiFi.config(ip, gateway, subnet);
  
  // Connect to WiFi network
  Serial.begin(115200);
  Serial.print("Connecting to ");
  Serial.println(ssid);
  
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.println("WiFi connected");
 
  // Start the server
  server.begin();
  Serial.println("Server started");
 
  // Print the IP address
  Serial.print("Use this URL : ");
  Serial.print("http://");
  Serial.print(WiFi.localIP());
  Serial.println("/");

  // Print a message to the LCD.
  lcd.init();
  lcd.backlight();
  lcd.setCursor(0,0);           // set cursor to column 0, row 0 (the first row)
  lcd.print("Running");

  //Set the energy monitor
  emon1.current(0, 60.60);  
  emon2.current(0, 60.60);
  
  //Set the pinMode for the switches
  pinMode(outletPin, OUTPUT);
  digitalWrite(outletPin, HIGH); 
  pinMode(lightPin, OUTPUT);
  digitalWrite(lightPin, HIGH);
}

void loop() {
  // put your main code here, to run repeatedly:
  process_request();
  if (outlet == HIGH){
    calibrateReading(20);
    displayReading("OUTLET",outletIrms,outletPower);
    postOutletReading();
  }else if (light == HIGH){
    calibrateReading(20);
    displayReading("LIGHT",lightIrms,lightPower);
    postLightReading();
  }else{
    lcd.clear();
    lcd.setCursor(0,0);           // Displays all current data
    lcd.print("HOME AUTOMATION");
    lcd.setCursor(0,1);  
    lcd.print("IP");
    lcd.setCursor(3,1);  
    lcd.print(WiFi.localIP());
    delay(5000);
  }
}

void displayReading(String deviceName, double Irms,double Power){
  lcd.clear();
  lcd.setCursor(0,0);           // Displays all current data
  lcd.print(deviceName);
  
  lcd.setCursor(7,0);
  lcd.print("P");
  
  lcd.setCursor(9,0);
  lcd.print(Power);
  lcd.print("W");
  
  lcd.setCursor(7,1);           // Displays all current data
  lcd.print("C");
  
  lcd.setCursor(9,1); 
  lcd.print(Irms);
  lcd.print("A");
}

void calibrateReading(int counter){
  while(calibration == false){
    if(counter >= 0){
      counter -= 1;
      outletIrms = emon1.calcIrms(1480); 
      lightIrms = emon2.calcIrms(1480);
      outletPower = outletIrms * 240.0;
      lightPower = lightIrms * 240.0;
      lcd.clear();
      lcd.setCursor(0,0); 
      lcd.print("Calibrating. . .");
      delay(100);
    }else{
      lcd.clear();
      lcd.setCursor(0,0); 
      lcd.print("Calibration");
      lcd.setCursor(0,1); 
      lcd.print("Done!");
      calibration = true;
      delay(1000);
    }
  }
  outletIrms = emon1.calcIrms(1480); 
  lightIrms = emon2.calcIrms(1480);
  outletPower = outletIrms * 240.0;
  lightPower = lightIrms * 240.0;
}

void postOutletReading(){
  String device_id = "5";
  postData(outletIrms,outletPower,device_id);
}

void postLightReading(){
  String device_id = "6";
  postData(lightIrms,lightPower,device_id);
}

void process_request(){
  // Check if a client has connected
  WiFiClient client = server.available();
  if (!client) {
    return;
  }
 
  // Wait until the client sends some data
  while(!client.available()){
    delay(1);
  }
 
  // Read the first line of the request
  String request = client.readStringUntil('\r');
  Serial.println(request);
  client.flush();
 
  // Match the request
 
  int outletvalue = LOW;
  if (request.indexOf("/?OUTLET=ON") != -1) {
    digitalWrite(outletPin, LOW);
    outletvalue = HIGH;
    outlet = HIGH;
  }
  if (request.indexOf("/?OUTLET=OFF") != -1){
    digitalWrite(outletPin, HIGH);
    outletvalue = LOW;
    outlet = LOW;
    calibration = false;
  }

 int lightvalue = LOW;
  if (request.indexOf("/?LIGHT=ON") != -1) {
    digitalWrite(lightPin, LOW);
    lightvalue = HIGH;
    light = HIGH;
  }
  if (request.indexOf("/?LIGHT=OFF") != -1){
    digitalWrite(lightPin, HIGH);
    lightvalue = LOW;
    light = LOW;
    calibration = false;
  }
  
  // Return the response
  client.println("HTTP/1.1 200 OK");
  client.println("Content-Type: text/html");
  client.println(""); //  do not forget this one
  client.println("<!DOCTYPE HTML>");
  client.println("<html>");
  client.println("ok");
  client.println("</html>");
}
void postData(double a,double w, String device_id){
  //SERVER PARAMETERS
  const int port= 80;
  String request_url ="/home_automation/main/device_consumption.php?";
  String device_info = "device_id=" + device_id + "&A=" + a + "&W=" + w;
  
  WiFiClient client;
  if(!client.connect(serverIP,port)){ //HTTP Connection
    Serial.println("Connection failed");
    return;
  }
  //Url which will insert data to database
  client.print(String("GET ") + request_url + device_info + " HTTP/1.0\r\n" + //get request to the server
               "Host: " + serverIP + "\r\n" + 
               "Connection: close\r\n\r\n");
  delay(500);
  
  //while(client.available()){
  //  String line = client.readStringUntil('\r');
  //  Serial.print(line);
  //}
}
