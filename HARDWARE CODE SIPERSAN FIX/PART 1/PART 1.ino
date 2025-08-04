#include <WiFi.h>
#include <WiFiManager.h>         // by tzapu
#include <HTTPClient.h>
#include <SPI.h>
#include <MFRC522.h>             // by Miguel Balboa
#include <Wire.h>
#include <LiquidCrystal_I2C.h>   // by Marco Schwartz
#include <ArduinoJson.h>
#include <time.h>

// ==========================
//     PIN SETUP
// ==========================
#define SS_PIN     21
#define RST_PIN    22
#define BUTTON_PIN 27
#define RGB_R      13
#define RGB_G      12
#define RGB_B      14
#define BUZZER     4

MFRC522 mfrc522(SS_PIN, RST_PIN);
LiquidCrystal_I2C lcd(0x27, 16, 2);

// ==========================
//     WiFiManager setup
// ==========================
char apiUrl[100] = "";
WiFiManagerParameter custom_api_url("api_url", "Base API URL", apiUrl, 100);

// ==========================
//     MODE ENUM
// ==========================
enum Mode { MODE_MASUK, MODE_KELUAR, MODE_DAFTAR };
Mode currentMode = MODE_MASUK;

String lastUID = "";
unsigned long lastScanTime = 0;
unsigned long lastButtonChange = 0;
bool lastButtonState = HIGH;
bool buttonHeld = false;

unsigned long setupWifiHoldTime = 0;
bool wifiSetupTriggered = false;

// ==========================
//     TAMPILKAN STATUS DI LCD
// ==========================
void lcdShowStatus(const String& baris1, const String& baris2, int delayMs = 1500) {
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print(baris1);
  lcd.setCursor(0, 1);
  lcd.print(baris2);
  delay(delayMs);
}

void setup() {
  Serial.begin(115200);

  // I/O hardware
  pinMode(BUTTON_PIN, INPUT_PULLUP);
  pinMode(RGB_R, OUTPUT); pinMode(RGB_G, OUTPUT); pinMode(RGB_B, OUTPUT); pinMode(BUZZER, OUTPUT);
  SPI.begin(18, 19, 23, SS_PIN);
  mfrc522.PCD_Init();
  Wire.begin(26, 25);
  lcd.init(); lcd.backlight();

  // ======================================
  //     MODE SETUP WIFI (tahan 5 detik)
  // ======================================
  if (digitalRead(BUTTON_PIN) == LOW) {
    lcdShowStatus("MODE: SETUP WIFI", "Tunggu Portal...");
    WiFiManager wm;
    wm.addParameter(&custom_api_url);
    wm.resetSettings();
    if (!wm.autoConnect("SIPERSAN-SETUP")) {
      lcdShowStatus("WiFi Gagal", "Cek Koneksi!", 2000);
      ESP.restart();
    }
    strcpy(apiUrl, custom_api_url.getValue());
    lcdShowStatus("WiFi Terhubung", WiFi.SSID());
    lcdShowStatus("API URL:", String(apiUrl).substring(0, 16));
    ESP.restart();
  }

  // ======================================
  //     MODE NORMAL: Auto connect
  // ======================================
  WiFiManager wm;
  wm.addParameter(&custom_api_url);
  wm.setConfigPortalTimeout(180);
  if (!wm.autoConnect("SIPERSAN-SETUP")) {
    lcdShowStatus("WiFi Gagal", "Cek Koneksi!", 2000);
    ESP.restart();
  }
  strcpy(apiUrl, custom_api_url.getValue());
  lcdShowStatus("WiFi: ", WiFi.SSID());
  lcdShowStatus("API URL:", String(apiUrl).substring(0, 16));

  Serial.print("✅ WiFi: "); Serial.println(WiFi.SSID());
  Serial.print("📡 API URL: "); Serial.println(apiUrl);

  configTime(25200, 0, "pool.ntp.org", "time.nist.gov"); // GMT+7

  printMode();
}

void loop() {
  bool btnState = digitalRead(BUTTON_PIN);

  // ========== MODE SETUP WIFI (tahan 5 detik)
  if (btnState == LOW && lastButtonState == HIGH) {
    lastButtonChange = millis();
    buttonHeld = false;
    setupWifiHoldTime = millis();
    wifiSetupTriggered = false;
  }
  if (btnState == LOW && !wifiSetupTriggered && (millis() - setupWifiHoldTime > 5000)) {
    wifiSetupTriggered = true;
    lcdShowStatus("MODE: SETUP WIFI", "Tunggu Portal...");
    Serial.println("⚙️ MASUK MODE SETUP WIFI");

    WiFi.disconnect(true);
    delay(500);
    WiFiManager wm;
    wm.addParameter(&custom_api_url);
    wm.resetSettings();
    if (!wm.autoConnect("SIPERSAN-SETUP")) {
      lcdShowStatus("WiFi Gagal", "Cek Koneksi!", 2000);
      ESP.restart();
    }
    strcpy(apiUrl, custom_api_url.getValue());
    lcdShowStatus("WiFi Terhubung", WiFi.SSID());
    lcdShowStatus("API URL:", String(apiUrl).substring(0, 16));
    ESP.restart();
  }

  // ========== MODE DAFTAR (tahan 3 detik)
  if (btnState == LOW && !buttonHeld && (millis() - lastButtonChange > 3000) && (millis() - setupWifiHoldTime < 5000)) {
    currentMode = MODE_DAFTAR;
    tone(BUZZER, 1500, 100); delay(100);
    tone(BUZZER, 900, 100); delay(100);
    noTone(BUZZER);
    lcd.clear(); lcd.setCursor(0, 0);
    lcd.print("MODE: DAFTAR UID");
    Serial.println("🟡 MODE: DAFTAR");
    buttonHeld = true; delay(500);
  }

  // ========== MODE TOGGLE MASUK/KELUAR (klik cepat)
  if (btnState == HIGH && lastButtonState == LOW && !buttonHeld && !wifiSetupTriggered) {
    currentMode = (currentMode == MODE_MASUK) ? MODE_KELUAR : MODE_MASUK;
    tone(BUZZER, 1000, 150); noTone(BUZZER);
    printMode(); delay(300);
  }
  lastButtonState = btnState;

  // ========== RFID scan
  if (!mfrc522.PICC_IsNewCardPresent() || !mfrc522.PICC_ReadCardSerial()) return;

  String uid = "";
  for (byte i = 0; i < mfrc522.uid.size; i++) {
    uid += (mfrc522.uid.uidByte[i] < 0x10 ? "0" : "");
    uid += String(mfrc522.uid.uidByte[i], HEX);
  }
  uid.toUpperCase();

  if (uid == lastUID && millis() - lastScanTime < 5000) return;
  lastUID = uid;
  lastScanTime = millis();

  String modeStr = (currentMode == MODE_KELUAR) ? "KELUAR" :
                   (currentMode == MODE_MASUK) ? "MASUK" : "DAFTAR";
  Serial.println("UID: " + uid + " MODE: " + modeStr);

  lcd.clear(); lcd.setCursor(0, 0); lcd.print("Memeriksa UID...");
  digitalWrite(RGB_R, LOW); digitalWrite(RGB_G, LOW);
  tone(BUZZER, 800, 150); delay(200); noTone(BUZZER);

  // Format waktu
  struct tm timeinfo;
  String waktu = "";
  if (getLocalTime(&timeinfo)) {
    char buffer[30];
    strftime(buffer, 30, "%Y-%m-%d %H:%M:%S", &timeinfo);
    waktu = String(buffer);
  }

  // Kirim ke API /perizinan
  HTTPClient http;
  String fullUrl = String(apiUrl) + "api/perizinan?uid=" + uid + "&mode=" + modeStr;
  http.begin(fullUrl); int res = http.GET();
  String body = http.getString(); http.end();

  // Simpan UID (opsional)
  HTTPClient http2;
  String simpanURL = String(apiUrl) + "api/simpan_uid?uid=" + uid + "&mode=" + modeStr;
  http2.begin(simpanURL); http2.GET(); http2.end();

  lcd.clear();
  if (currentMode == MODE_DAFTAR) {
    lcd.setCursor(0, 0); lcd.print("Kartu Terdaftar");
    digitalWrite(RGB_G, HIGH);
    tone(BUZZER, 1000, 200); delay(200); noTone(BUZZER);
  } else if (body == "OK") {
    lcd.setCursor(0, 0); lcd.print("Kartu Valid");
    digitalWrite(RGB_G, HIGH);
    tone(BUZZER, 900, 150); delay(150); noTone(BUZZER);
  } else if (body == "NOT FOUND") {
    lcd.setCursor(0, 0); lcd.print("Tidak Terdaftar");
    digitalWrite(RGB_R, HIGH);
    tone(BUZZER, 700, 300); delay(300); noTone(BUZZER);
  } else {
    lcd.setCursor(0, 0); lcd.print("Server Error");
    lcd.setCursor(0, 1); lcd.print("Periksa API!");
    digitalWrite(RGB_R, HIGH);
  }

  delay(2000);
  digitalWrite(RGB_R, LOW); digitalWrite(RGB_G, LOW); printMode();
}

void printMode() {
  lcd.clear(); lcd.setCursor(0, 0);
  String modeStr = (currentMode == MODE_KELUAR) ? "🔴 KELUAR" :
                   (currentMode == MODE_MASUK) ? "🟢 MASUK" : "🟡 DAFTAR";
  lcd.print("MODE: " + modeStr);
}
