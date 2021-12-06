//Enccabezados
#include <SPI.h>
#include <WiFiNINA.h>
#include "arduino_secrets.h" 
#include "ArduinoJson.h"

#include<ADS1115_WE.h> 
#include<Wire.h>
#define I2C_ADDRESS 0x48
ADS1115_WE adc(I2C_ADDRESS);


// VARIABLES
char ssid[] = SECRET_SSID;  //SSID de la red


char pass[] = SECRET_PASS;  //clave de la red
int keyIndex = 0;           // your network key Index number (needed only for WEP)
int status = WL_IDLE_STATUS;//estado conexión

//DIRECCION SERVIDOR **********************************************************************************************************
//const char* host = "192.168.43.167"; //Conexion a portatil conectado a celular jorge cock, verificar con IPCONFIG
//const char* host = "127.0.0.1"; //Conexion celular jorge cock
//const char* host = "jorgecock.byethost5.com"; //Conexion celular jorge cock??
//const char* host = "192.168.1.15"; //Servidor Casa Jorge
//const char* host = "192.168.1.158"; //Servidor Carsil
const char* host = "10.171.92.68"; //Servidor pcKarolina

const int httpPort = 80;


//Codigo***********

//Direccion API****************************************************************************************************************
//String url = "http://jorgecock.byethost5.com/ControldeEstados/api/apiIoT.php";
//String url = "http://192.168.1.158/ControldeEstados/api/apiIoT.php";
String url = "http://localhost/ControldeEstados1/sistema/api/apiIoT.php"; //carsil y casa  jorge


int estadosensor1 =0;
int estadosensoranterior1=0;
int estadosensor2 =0;
int estadosensoranterior2=0;
int Input1 = 5; //puerto entrada en placa Arduino MRK1010 para Pulsador produccion 
int Input2 = 4; //puerto entrada en placa Arduino MRK1010 para Paro de emergencia 
int Output1 = 3; //puerto Salida en placa Arduino MRK1010 para Led amarillo de pulsado boton verde 
int Output2 = 2; //puerto Salida en placa Arduino MRK1010 para Led azul     estado y comunicaciones 
int Output3 = 1; //puerto Salida en placa Arduino MRK1010 para Led rojo     de pulsado boton rojo
float voltage = 0.0; //salida del ADC
int contreg=0;

//******************DATOS DEL TIPO DE MODULO Y SERIE***************************************************************************
int iddispositivoiot=2; // NUMERO SERIAL DEL DISPOSITIOV IOT
int idtipodispositivoiot=1; // NUMERO SERIAL DEL DISPOSITIOV IOT
//*****************************************************************************************************************************

// CONFIGURACIÓN INICIAL
void setup() {
  //Inicio de puertos
  pinMode(Input1, INPUT_PULLUP);
  pinMode(Input2, INPUT_PULLUP);
  pinMode(LED_BUILTIN, OUTPUT);
  pinMode(Output1, OUTPUT);
  pinMode(Output2, OUTPUT);
  pinMode(Output3, OUTPUT);
  digitalWrite(Output1, 1);
  digitalWrite(Output2, 1);
  digitalWrite(Output3, 1);
  
  //Inicializacion serial
  Serial.begin(115200);
  //while (!Serial) {
    ;//esperar inicio serial
  //}
  //delay(100);

  //Inicio ADC para medida de corriente
  Wire.begin();
  if(!adc.init()){
    //anuncia que no está conectado el modulo ADC
    Serial.println("ADS1115 not connected!");
    for (int i = 0; i <= 10; i++) {
      digitalWrite(Output2, 0);
      delay(20);
      digitalWrite(Output2, 1);
      delay(20);
    }
    delay(1000);
  }
  adc.setVoltageRange_mV(ADS1115_RANGE_6144);
  adc.setCompareChannels(ADS1115_COMP_0_1);
  adc.setMeasureMode(ADS1115_CONTINUOUS);


  //Primera Lectura
  estadosensor1 = digitalRead(Input1);
  digitalWrite(Output1, !estadosensor1);
  estadosensor2 = digitalRead(Input2);
  digitalWrite(Output3, !estadosensor2);
  Serial.print("Estado sensor1");
  Serial.println(estadosensor1);
  Serial.print("Estado sensor2");
  Serial.println(estadosensor2);
  Serial.println("************");
  
  //Validar wifi en modulo
  if (WiFi.status() == WL_NO_MODULE) {
    Serial.println("comunicacion con WiFi fallada");
    while (true);
    //Mensaje señal de error 
    digitalWrite(Output2, 0);
    delay(20);
    digitalWrite(Output2, 1);
    delay(20);
  }

  //Validar version firmware wifi en modulo
  String fv = WiFi.firmwareVersion();
  if (fv < "1.0.0") {
    Serial.print("Actualizar el firmware");
  }

  ///Conectar a red.
  Serial.print("conectando a SSID: ");
  Serial.println(ssid);
  WiFi.begin(ssid, pass); 
  while (WiFi.status() != WL_CONNECTED) {
    status = WiFi.begin(ssid, pass);
    digitalWrite(Output2, 0);
    delay(200);
    digitalWrite(Output2, 1);
    delay(200);
    Serial.print(".");
  }

  //conectado
  digitalWrite(Output1, 1);
  digitalWrite(Output2, 1);
  Serial.println("WiFi Conectado");  
  Serial.println("IP address: ");
  Serial.println(WiFi.localIP());
  Serial.print("Netmask: ");
  Serial.println(WiFi.subnetMask());
  Serial.print("Gateway: ");
  Serial.println(WiFi.gatewayIP());
  Serial.print("Status: ");
  Serial.println(WiFi.status());
  Serial.println("Conexión con red OK");
  Serial.println("***************************************************");
  delay(100);

}
 
void loop() {
  voltage = adc.getResult_V();
  estadosensor1 = digitalRead(Input1);
  digitalWrite(Output1, !estadosensor1);
  estadosensor2 = !digitalRead(Input2); //se niega por ser un boton normalmente cerrado
  digitalWrite(Output3, !estadosensor2);
  
  if ( ((estadosensor1!=estadosensoranterior1) and estadosensor1==1) or ((estadosensor2!=estadosensoranterior2) and estadosensor2==1) ){
    contreg=contreg+1;;
    Serial.print("Conteo: ");
    Serial.println(contreg);
    Serial.print("Estado sensor1: ");
    Serial.println(estadosensor1);
    Serial.print("Estado sensor2: ");
    Serial.println(estadosensor2);
    Serial.print("Voltage medido: ");
    Serial.println(voltage);
    Serial.println("************");

    
    //Concetarse a la base de datos
    WiFiClient client;
    delay(300);
    if (!client.connect(host, httpPort)) {
      Serial.println("Conexion fallada al servidor");
      digitalWrite(Output2, 0);
      delay(30);
      digitalWrite(Output2, 1);
      delay(30);
      digitalWrite(Output2, 0);
      delay(30);
      digitalWrite(Output2, 1);
      delay(30);
      digitalWrite(Output2, 0);
      delay(30);
      digitalWrite(Output2, 1);
      delay(30);
      return;
    }else{
      Serial.println("Conectado a:"+ String(host) +":"+String (httpPort)+" correctamente.");
      Serial.println();
    }

    //comando para la base de datos a traves de API.
    String data= "iddispositivoiot="+String(iddispositivoiot)+"&idtipodispositivoiot="+String(idtipodispositivoiot)+"&boton1="+String(estadosensor1)+"&boton2="+String(estadosensor2)+"&voltage="+String(voltage);
    Serial.println("Solicitando: ");
    
    //envio de comando por monitor serial para visualizar lo enviado y verificar.
    Serial.print(String("GET ") + url+ "?" + data+" HTTP/1.0\r\n"+
              "Host: " + host + "\r\n" +
              "Accept: *" + "/" +"*\r\n" +
              "Content-Length: " + data.length() + "\r\n" +
              "Content-Type: application/x-www-form-urlencoded\r\n" +
              "\r\n" + data);

    //envio de comando por wifi a servidor, mismo comando
    client.print(String("GET ") + url+ "?" + data+" HTTP/1.0\r\n"+
              "Host: " + host + "\r\n" +
              "Accept: *" + "/" +"*\r\n" +
              "Content-Length: " + data.length() + "\r\n" +
              "Content-Type: application/x-www-form-urlencoded\r\n" +
              "\r\n" + data);

    Serial.println("");
    delay(2000);
    Serial.println("************");
    Serial.println("Respuesta:");
    
    //leer respuesta serial Wifi
    while(client.available()){
      String line = client.readStringUntil('\r');
      Serial.print(line);
    }
    Serial.println("");

    /*if (!client.connected()) {
      Serial.println();
      Serial.println("Desconectado del servidor.");
      client.stop();
      // hacer nada
      while (true);
    }*/

    Serial.println("************");
    Serial.println("Cerrando conexion");
    client.stop();
    
    Serial.println("***************************************************");
    Serial.println("\r\n");
  }
  
  estadosensoranterior1=estadosensor1;
  estadosensoranterior2=estadosensor2;
  digitalWrite(Output2, 0);
  delay(100);
  digitalWrite(Output2, 1);
  delay(100);
}
