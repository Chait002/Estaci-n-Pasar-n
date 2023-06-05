// Include libraries
#include <HTTPClient.h>
#include <WiFi.h>

// Add WIFI data
const char* ssid = "mywifiname";              // Add your WIFI network name 
const char* password =  "12345678";           // Add WIFI password

// Variables used in the code
String LED_id = "1";                          // Just in case you control more than 1 LED
String data_to_send = "";                     // Text data to send to the server
unsigned int Actual_Millis, Previous_Millis;
int refresh_time = 200;                       // Refresh rate of connection to website (recommended more than 1s)

// Inputs/outputs
const int ledPin1 = 2;
const int ledPin2 = 3;
const int ledPin3 = 4;
const int ledPin4 = 5;
const int ledPin5 = 6;

void setup() {
  delay(10);
  Serial.begin(115200);                       // Start monitor
  pinMode(ledPin1, OUTPUT);
  pinMode(ledPin2, OUTPUT);
  pinMode(ledPin3, OUTPUT);
  pinMode(ledPin4, OUTPUT);
  pinMode(ledPin5, OUTPUT);

  WiFi.begin(ssid, password);                 // Start wifi connection
  Serial.print("Connecting...");
  while (WiFi.status() != WL_CONNECTED) {     // Check for the connection
    delay(500);
    Serial.print(".");
  }

  Serial.print("Connected, my IP: ");
  Serial.println(WiFi.localIP());
  Actual_Millis = millis();                    // Save time for refresh loop
  Previous_Millis = Actual_Millis; 
}

void loop() {
  // We make the refresh loop using millis() so we don't have to use delay();
  Actual_Millis = millis();
  if (Actual_Millis - Previous_Millis > refresh_time) {
    Previous_Millis = Actual_Millis;
    if (WiFi.status() == WL_CONNECTED) {       // Check WiFi connection status
      HTTPClient http;                          // Create new client
      data_to_send = "check_LED_status=" + LED_id;  // Send text: "check_LED_status"
      
      // Begin new connection to website
      http.begin("https://pasaron.000webhostapp.com/index.php"); // Indicate the destination webpage 
      http.addHeader("Content-Type", "application/x-www-form-urlencoded");         // Prepare the header
      
      int response_code = http.POST(data_to_send);  // Send the POST. This will give us a response code
      
      // If the code is higher than 0, it means we received a response
      if (response_code > 0) {
        Serial.println("HTTP code " + String(response_code));  // Print return code
  
        if (response_code == HTTP_CODE_OK) {                   // If code is 200, we received a good response and we can read the echo data
          String response_body = http.getString();            // Save the data coming from the website
          Serial.print("Server reply: ");                      // Print data to the monitor for debug
          Serial.println(response_body);

          // If the received data is LED_is_off, we set LOW the LED pins
          if (response_body == "LED_is_off") {
            digitalWrite(ledPin1, LOW);
            digitalWrite(ledPin2, LOW);
            digitalWrite(ledPin3, LOW);
            digitalWrite(ledPin4, LOW);
            digitalWrite(ledPin5, LOW);
          }
          // If the received data is LED_is_on, we set HIGH the LED pins
          else if (response_body == "LED_is_on") {
            digitalWrite(ledPin1, HIGH);
            digitalWrite(ledPin5, HIGH);
            delay(200);
            digitalWrite(ledPin2, HIGH);
            digitalWrite(ledPin4, HIGH);
            delay(200);
            digitalWrite(ledPin3, HIGH);
            delay(200);
            digitalWrite(ledPin1, HIGH);
            digitalWrite(ledPin2, HIGH);
            digitalWrite(ledPin3, HIGH);
            digitalWrite(ledPin5, HIGH);
            digitalWrite(ledPin4, HIGH);
          }
        }
      }
      else {
        Serial.print("Error sending POST, code: ");
        Serial.println(response_code);
      }
      http.end();   // End the connection
    }
    else {
      Serial.println("WIFI connection error");
    }
  }
}
