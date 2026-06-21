-- ============================================================
-- TT Electro Store — Demo Seed Data
-- Import AFTER schema.sql on a fresh database.
-- ============================================================
-- Categories auto-IDs (from schema.sql INSERT order):
--   1=Microcontrollers  2=Sensors       3=Displays
--   4=Power Modules     5=Communication 6=Robotics
--   7=3D Printing       8=Tools         9=Cables & Connectors  10=Kits
-- Brand auto-IDs:
--   1=Arduino  2=Raspberry Pi  3=ESP32  4=Adafruit  5=STMicro  6=Generic
-- ============================================================

SET NAMES utf8mb4;

-- ===========================================================
-- PRODUCTS (25 — spans all 10 categories)
-- ===========================================================
INSERT INTO `products`
  (`name`,`slug`,`description`,`price`,`original_price`,`discount`,`stock`,`thumbnail`,`images`,`tags`,`specifications`,
   `category_id`,`brand_id`,`is_featured`,`is_trending`,`is_best_seller`,`is_flash_sale`,`flash_sale_price`,`flash_sale_ends`,`is_active`)
VALUES

-- ── Microcontrollers ─────────────────────────────────────────
(
  'Arduino Uno R3',
  'arduino-uno-r3',
  'The iconic Arduino Uno R3 based on the ATmega328P microcontroller. Ideal for beginners and prototyping. Includes USB-B cable. With 14 digital I/O pins, 6 PWM outputs and 6 analog inputs, it is the most popular development board in the world.',
  599.00, 799.00, 25.03, 150,
  'https://picsum.photos/seed/arduino-uno-r3/600/600',
  '["https://picsum.photos/seed/arduino-uno-r3/600/600","https://picsum.photos/seed/arduino-uno-r3b/600/600"]',
  '["arduino","microcontroller","beginner","atmega328p","uno"]',
  '[{"key":"Microcontroller","value":"ATmega328P"},{"key":"Operating Voltage","value":"5V"},{"key":"Input Voltage","value":"7–12V"},{"key":"Digital I/O Pins","value":"14 (6 PWM)"},{"key":"Analog Input Pins","value":"6"},{"key":"Flash Memory","value":"32 KB"},{"key":"SRAM","value":"2 KB"},{"key":"Clock Speed","value":"16 MHz"}]',
  1, 1, 1, 0, 1, 0, NULL, NULL, 1
),
(
  'Arduino Nano Every',
  'arduino-nano-every',
  'The compact Arduino Nano Every packs serious power into a tiny DIP form factor. Based on ATMega4809, it offers more memory and faster processing than the classic Nano. Perfect for space-constrained projects.',
  349.00, 499.00, 30.06, 200,
  'https://picsum.photos/seed/arduino-nano-every/600/600',
  '["https://picsum.photos/seed/arduino-nano-every/600/600"]',
  '["arduino","nano","compact","atmega4809","breadboard"]',
  '[{"key":"Microcontroller","value":"ATMega4809"},{"key":"Operating Voltage","value":"5V"},{"key":"Digital I/O Pins","value":"14"},{"key":"Analog Input Pins","value":"8"},{"key":"Flash Memory","value":"48 KB"},{"key":"SRAM","value":"6 KB"},{"key":"Clock Speed","value":"20 MHz"}]',
  1, 1, 0, 1, 0, 0, NULL, NULL, 1
),
(
  'ESP32 DevKit V1',
  'esp32-devkit-v1',
  'The ESP32 DevKit V1 is a feature-rich WiFi + Bluetooth development board built around the Espressif ESP32 chip. Ideal for IoT, home automation, and wireless projects. Dual-core 240 MHz processor with built-in WiFi and BLE.',
  449.00, 599.00, 25.04, 300,
  'https://picsum.photos/seed/esp32-devkit/600/600',
  '["https://picsum.photos/seed/esp32-devkit/600/600","https://picsum.photos/seed/esp32-devkit-b/600/600"]',
  '["esp32","wifi","bluetooth","iot","dual-core"]',
  '[{"key":"Processor","value":"Xtensa Dual-Core 32-bit LX6"},{"key":"Clock Speed","value":"240 MHz"},{"key":"Flash Memory","value":"4 MB"},{"key":"SRAM","value":"520 KB"},{"key":"WiFi","value":"802.11 b/g/n 2.4 GHz"},{"key":"Bluetooth","value":"BLE 4.2 + Classic"},{"key":"GPIO Pins","value":"38"},{"key":"Operating Voltage","value":"3.3V"}]',
  1, 3, 1, 1, 0, 0, NULL, NULL, 1
),
(
  'Raspberry Pi Pico W',
  'raspberry-pi-pico-w',
  'The Raspberry Pi Pico W adds on-board WiFi (802.11n) to the popular RP2040-based Pico. Programme it with MicroPython or C/C++. Dual-core ARM Cortex-M0+ at 133 MHz with 264KB SRAM and flexible I/O.',
  749.00, 999.00, 25.02, 120,
  'https://picsum.photos/seed/rpi-pico-w/600/600',
  '["https://picsum.photos/seed/rpi-pico-w/600/600"]',
  '["raspberry-pi","pico","micropython","rp2040","wifi"]',
  '[{"key":"Processor","value":"RP2040 Dual-Core ARM Cortex-M0+"},{"key":"Clock Speed","value":"133 MHz"},{"key":"SRAM","value":"264 KB"},{"key":"Flash","value":"2 MB"},{"key":"WiFi","value":"802.11n 2.4 GHz"},{"key":"GPIO Pins","value":"26"},{"key":"ADC","value":"3× 12-bit"},{"key":"Operating Voltage","value":"1.8–5.5V"}]',
  1, 2, 1, 1, 0, 0, NULL, NULL, 1
),

-- ── Sensors ─────────────────────────────────────────────────
(
  'DHT22 Temperature & Humidity Sensor',
  'dht22-temp-humidity-sensor',
  'The DHT22 (AM2302) is a calibrated digital temperature and humidity sensor with a single-wire interface. Wider range and higher accuracy than DHT11. Compatible with Arduino, ESP32, and Raspberry Pi.',
  199.00, 299.00, 33.44, 500,
  'https://picsum.photos/seed/dht22-sensor/600/600',
  '["https://picsum.photos/seed/dht22-sensor/600/600"]',
  '["sensor","temperature","humidity","dht22","iot","climate"]',
  '[{"key":"Temperature Range","value":"-40°C to +80°C"},{"key":"Temperature Accuracy","value":"±0.5°C"},{"key":"Humidity Range","value":"0–100% RH"},{"key":"Humidity Accuracy","value":"±2–5% RH"},{"key":"Sampling Rate","value":"0.5 Hz"},{"key":"Operating Voltage","value":"3.3–6V"},{"key":"Interface","value":"Single-wire digital"}]',
  2, 6, 0, 1, 0, 1, 149.00, '2026-06-22 23:59:59', 1
),
(
  'HC-SR04 Ultrasonic Distance Sensor',
  'hc-sr04-ultrasonic-sensor',
  'The HC-SR04 ultrasonic distance sensor measures distance from 2 cm to 400 cm with high accuracy. Uses ultrasonic pulses and echo return time. Great for robotics, obstacle detection, and level sensing.',
  99.00, 149.00, 33.56, 800,
  'https://picsum.photos/seed/hcsr04-sensor/600/600',
  '["https://picsum.photos/seed/hcsr04-sensor/600/600"]',
  '["sensor","ultrasonic","distance","hc-sr04","robotics"]',
  '[{"key":"Measuring Range","value":"2 cm – 400 cm"},{"key":"Accuracy","value":"3 mm"},{"key":"Measuring Angle","value":"15°"},{"key":"Operating Voltage","value":"5V DC"},{"key":"Operating Current","value":"15 mA"},{"key":"Trigger Input","value":"10µs TTL pulse"},{"key":"Dimensions","value":"45×20×15 mm"}]',
  2, 6, 0, 0, 1, 0, NULL, NULL, 1
),
(
  'MPU-6050 6-Axis Accelerometer & Gyroscope',
  'mpu6050-accelerometer-gyroscope',
  'The MPU-6050 is a combined 3-axis accelerometer and 3-axis gyroscope on a single chip. Communicates over I2C. Used in drones, balancing robots, wearables, and motion capture projects.',
  149.00, 249.00, 40.16, 600,
  'https://picsum.photos/seed/mpu6050/600/600',
  '["https://picsum.photos/seed/mpu6050/600/600"]',
  '["sensor","imu","gyroscope","accelerometer","mpu6050","drone"]',
  '[{"key":"Accelerometer Range","value":"±2g / ±4g / ±8g / ±16g"},{"key":"Gyroscope Range","value":"±250 / ±500 / ±1000 / ±2000 °/s"},{"key":"Interface","value":"I2C (up to 400kHz)"},{"key":"Operating Voltage","value":"3.3–5V"},{"key":"ADC Resolution","value":"16-bit"},{"key":"Built-in","value":"Temperature sensor, DMP"}]',
  2, 6, 0, 0, 0, 1, 99.00, '2026-06-22 23:59:59', 1
),
(
  'MQ-135 Air Quality Gas Sensor',
  'mq135-air-quality-sensor',
  'The MQ-135 detects NH3, NOx, alcohol, benzene, smoke, and CO2. Outputs both analog and digital signals. Widely used in air quality monitors, ventilation systems, and smart home projects.',
  129.00, 199.00, 35.17, 400,
  'https://picsum.photos/seed/mq135-sensor/600/600',
  '["https://picsum.photos/seed/mq135-sensor/600/600"]',
  '["sensor","gas","air-quality","mq135","smoke","co2"]',
  '[{"key":"Target Gases","value":"NH3, NOx, Alcohol, CO2, Benzene"},{"key":"Output","value":"Analog + Digital (TTL)"},{"key":"Heater Voltage","value":"5V AC/DC"},{"key":"Operating Voltage","value":"5V"},{"key":"Load Resistance","value":"Adjustable"},{"key":"Preheat Time","value":"20 seconds"}]',
  2, 6, 0, 0, 0, 0, NULL, NULL, 1
),

-- ── Displays ────────────────────────────────────────────────
(
  '0.96" OLED Display I2C (SSD1306)',
  'oled-096-display-i2c',
  'A crisp 0.96 inch OLED display using the SSD1306 driver over I2C. No backlight needed — emissive pixels provide excellent contrast even in bright light. Only 2 wires (SDA+SCL). Compatible with all Arduino, ESP32, and STM32 boards.',
  249.00, 349.00, 28.65, 350,
  'https://picsum.photos/seed/oled-display-ssd1306/600/600',
  '["https://picsum.photos/seed/oled-display-ssd1306/600/600"]',
  '["display","oled","ssd1306","i2c","128x64","screen"]',
  '[{"key":"Size","value":"0.96 inch"},{"key":"Resolution","value":"128×64 pixels"},{"key":"Driver IC","value":"SSD1306"},{"key":"Interface","value":"I2C (address 0x3C)"},{"key":"Operating Voltage","value":"3.3–5V"},{"key":"Colors","value":"Monochrome (Blue/White/Yellow-Blue)"},{"key":"Viewing Angle","value":"160°"}]',
  3, 6, 1, 0, 1, 0, NULL, NULL, 1
),
(
  '16x2 LCD Display with I2C Backpack',
  '16x2-lcd-i2c-display',
  '16-column × 2-row LCD display with a soldered I2C backpack (PCF8574) that reduces the wiring from 8 pins to just 2 (SDA+SCL). Blue backlight with contrast potentiometer. Plug-and-play with the LiquidCrystal_I2C library.',
  149.00, 229.00, 34.93, 400,
  'https://picsum.photos/seed/lcd-16x2-i2c/600/600',
  '["https://picsum.photos/seed/lcd-16x2-i2c/600/600"]',
  '["display","lcd","16x2","i2c","backlight","pcf8574"]',
  '[{"key":"Columns × Rows","value":"16 × 2"},{"key":"Backlight","value":"Blue LED"},{"key":"Interface","value":"I2C via PCF8574 backpack"},{"key":"I2C Address","value":"0x27 (adjustable)"},{"key":"Operating Voltage","value":"5V"},{"key":"Character Size","value":"5×8 dots"},{"key":"Contrast","value":"Adjustable via potentiometer"}]',
  3, 6, 0, 0, 1, 0, NULL, NULL, 1
),
(
  '2.4" TFT LCD Touchscreen Shield (ILI9341)',
  '2-4-tft-lcd-touchscreen-ili9341',
  'Full-colour 2.4 inch TFT LCD with resistive touchscreen. Uses the ILI9341 SPI driver. Plugs directly on top of Arduino Uno/Mega. Includes SD card slot for images. Drive up to 65,536 colours at 240×320 resolution.',
  499.00, 699.00, 28.61, 180,
  'https://picsum.photos/seed/tft-240x320/600/600',
  '["https://picsum.photos/seed/tft-240x320/600/600"]',
  '["display","tft","touchscreen","ili9341","spi","colour","arduino-shield"]',
  '[{"key":"Size","value":"2.4 inch"},{"key":"Resolution","value":"240×320 pixels"},{"key":"Driver IC","value":"ILI9341"},{"key":"Interface","value":"SPI"},{"key":"Colours","value":"65,536 (16-bit)"},{"key":"Touch","value":"Resistive (XPT2046)"},{"key":"SD Card","value":"Yes"},{"key":"Operating Voltage","value":"3.3–5V"}]',
  3, 4, 0, 0, 0, 0, NULL, NULL, 1
),

-- ── Power Modules ────────────────────────────────────────────
(
  'LM2596 DC-DC Buck Converter Module',
  'lm2596-buck-converter',
  'Adjustable step-down (buck) converter based on LM2596. Input 4–40V, output 1.25–37V continuously adjustable. Up to 3A output current. On-board LED voltmeter display for easy monitoring. Ideal for powering 3.3V and 5V circuits from a higher voltage source.',
  119.00, 189.00, 37.03, 600,
  'https://picsum.photos/seed/lm2596-buck/600/600',
  '["https://picsum.photos/seed/lm2596-buck/600/600"]',
  '["power","buck-converter","lm2596","step-down","regulator","dc-dc"]',
  '[{"key":"Input Voltage","value":"4–40V DC"},{"key":"Output Voltage","value":"1.25–37V (adjustable)"},{"key":"Max Output Current","value":"3A"},{"key":"Efficiency","value":"Up to 92%"},{"key":"Switching Frequency","value":"150 kHz"},{"key":"Display","value":"LED voltmeter"}]',
  4, 6, 0, 1, 0, 0, NULL, NULL, 1
),
(
  'TP4056 Li-Ion Battery Charger Module (USB-C)',
  'tp4056-battery-charger-usb-c',
  'Single-cell lithium-ion/LiPo battery charger and protection circuit. USB-C input. Charges at up to 1A with CC/CV profile. Built-in overcharge, over-discharge, and short-circuit protection. Dual-colour LED charge indicator.',
  59.00, 99.00, 40.40, 1000,
  'https://picsum.photos/seed/tp4056-charger/600/600',
  '["https://picsum.photos/seed/tp4056-charger/600/600"]',
  '["power","battery-charger","tp4056","lipo","lithium","usb-c","protection"]',
  '[{"key":"Input Voltage","value":"4.5–5.5V (USB-C)"},{"key":"Charge Current","value":"Up to 1A (1C)"},{"key":"Charge Voltage","value":"4.2V ±1%"},{"key":"Protection","value":"Overcharge + Overdischarge + Short-circuit"},{"key":"Indicator","value":"Red=charging, Blue=full"},{"key":"Cell Type","value":"3.7V Li-Ion / LiPo"}]',
  4, 6, 0, 0, 1, 0, NULL, NULL, 1
),

-- ── Communication ────────────────────────────────────────────
(
  'HC-05 Bluetooth Serial Module',
  'hc05-bluetooth-module',
  'Classic HC-05 Bluetooth 2.0 SPP (Serial Port Profile) module with AT command support. Works as both master and slave. Range up to 10 m. Pairs easily with smartphones, PCs, and other HC-05/HC-06 modules. Configurable baud rate via AT commands.',
  299.00, 449.00, 33.40, 250,
  'https://picsum.photos/seed/hc05-bluetooth/600/600',
  '["https://picsum.photos/seed/hc05-bluetooth/600/600"]',
  '["bluetooth","serial","hc-05","wireless","uart","at-commands"]',
  '[{"key":"Bluetooth Version","value":"2.0 SPP"},{"key":"Frequency","value":"2.4 GHz ISM"},{"key":"Range","value":"Up to 10 m (Class 2)"},{"key":"Operating Voltage","value":"3.3V (5V tolerant pins)"},{"key":"Interface","value":"UART (TX/RX)"},{"key":"Default Baud Rate","value":"9600 bps"},{"key":"Mode","value":"Master / Slave (AT configurable)"}]',
  5, 6, 0, 0, 1, 0, NULL, NULL, 1
),
(
  'NRF24L01+ 2.4GHz RF Transceiver Module',
  'nrf24l01-rf-transceiver',
  'The nRF24L01+ is a single-chip 2.4GHz wireless transceiver with SPI interface. Supports 1 Mbps and 2 Mbps air data rates. Up to 100 m range in open air. Low power — perfect for wireless sensor networks, RC controls, and multi-node projects.',
  199.00, 299.00, 33.44, 400,
  'https://picsum.photos/seed/nrf24l01-rf/600/600',
  '["https://picsum.photos/seed/nrf24l01-rf/600/600"]',
  '["rf","wireless","nrf24l01","2.4ghz","spi","transceiver","iot"]',
  '[{"key":"Frequency","value":"2.4–2.5 GHz ISM"},{"key":"Air Data Rate","value":"250 kbps / 1 Mbps / 2 Mbps"},{"key":"Range","value":"Up to 100 m (open air)"},{"key":"Operating Voltage","value":"1.9–3.6V"},{"key":"Interface","value":"SPI (up to 10 Mbps)"},{"key":"Channels","value":"126"},{"key":"Transmit Power","value":"0 dBm max"}]',
  5, 6, 0, 0, 0, 1, 149.00, '2026-06-22 23:59:59', 1
),
(
  'SIM800L GSM GPRS Module',
  'sim800l-gsm-gprs-module',
  'The SIM800L is a compact quad-band GSM/GPRS module that supports SMS, voice calls, and GPRS data. Comes with a SIM card slot, PCB antenna, and power filtering capacitors. Controlled via AT commands. Perfect for IoT projects that need cellular connectivity.',
  599.00, 849.00, 29.44, 100,
  'https://picsum.photos/seed/sim800l-gsm/600/600',
  '["https://picsum.photos/seed/sim800l-gsm/600/600"]',
  '["gsm","gprs","sim800l","cellular","iot","sms","at-commands"]',
  '[{"key":"Frequency Bands","value":"850/900/1800/1900 MHz (quad-band)"},{"key":"Data","value":"GPRS Class 10 (85.6 kbps)"},{"key":"SMS","value":"MT/MO/CB/Text/PDU"},{"key":"Voice","value":"Yes (AT command)"},{"key":"SIM Slot","value":"1.8V / 3V SIM"},{"key":"Operating Voltage","value":"3.4–4.4V (needs 2A peak)"},{"key":"Interface","value":"UART (AT commands)"}]',
  5, 6, 0, 0, 0, 0, NULL, NULL, 1
),

-- ── Robotics ────────────────────────────────────────────────
(
  'L298N Dual H-Bridge Motor Driver',
  'l298n-motor-driver',
  'The L298N dual H-bridge motor driver can control 2 DC motors or 1 stepper motor. Up to 2A per channel, 46V supply voltage. Built-in 5V regulator powers your microcontroller. Logic-level inputs (2.5–5V) compatible with Arduino and ESP32.',
  179.00, 269.00, 33.46, 450,
  'https://picsum.photos/seed/l298n-motor-driver/600/600',
  '["https://picsum.photos/seed/l298n-motor-driver/600/600"]',
  '["motor-driver","l298n","dc-motor","stepper","robotics","h-bridge"]',
  '[{"key":"Channels","value":"2 DC motors or 1 stepper"},{"key":"Max Motor Voltage","value":"46V"},{"key":"Max Output Current","value":"2A per channel"},{"key":"Peak Current","value":"3A"},{"key":"Logic Voltage","value":"2.5–5V (TTL compatible)"},{"key":"On-board","value":"5V regulator, power LED, heat sink"}]',
  6, 6, 0, 1, 1, 0, NULL, NULL, 1
),
(
  'SG90 Micro Servo Motor 9g',
  'sg90-micro-servo-motor',
  'The SG90 micro servo is the go-to servo for light-duty projects. 9g weight, 180° rotation, plastic gears. Includes 3 horns, 2 mounting screws. Controlled with PWM (50 Hz, 1–2 ms pulse width). Works with Arduino, ESP32, and Raspberry Pi.',
  149.00, 229.00, 34.93, 700,
  'https://picsum.photos/seed/sg90-servo/600/600',
  '["https://picsum.photos/seed/sg90-servo/600/600"]',
  '["servo","motor","sg90","pwm","robotics","rc"]',
  '[{"key":"Weight","value":"9 g"},{"key":"Torque","value":"1.8 kg·cm (4.8V)"},{"key":"Speed","value":"0.1 s/60° (4.8V)"},{"key":"Rotation","value":"0–180°"},{"key":"Control","value":"PWM 50 Hz (1–2 ms)"},{"key":"Operating Voltage","value":"4.8–6V"},{"key":"Connector","value":"JR / Futaba standard"}]',
  6, 6, 0, 0, 1, 0, NULL, NULL, 1
),
(
  '2WD Robot Car Chassis Kit',
  '2wd-robot-car-chassis',
  'Complete 2-wheel drive robot car platform. Includes acrylic base plate, 2 DC gear motors (1:48), 2 wheels, 1 caster wheel, motor brackets, battery holder, and all fasteners. Perfect base for line-follower, obstacle-avoidance, and Bluetooth-controlled robots.',
  549.00, 799.00, 31.28, 80,
  'https://picsum.photos/seed/robot-car-chassis/600/600',
  '["https://picsum.photos/seed/robot-car-chassis/600/600"]',
  '["robot","chassis","2wd","dc-motor","kit","diy"]',
  '[{"key":"Drive","value":"2WD (2 gear motors)"},{"key":"Gear Ratio","value":"1:48"},{"key":"Motor Voltage","value":"3–6V DC"},{"key":"Speed (no load)","value":"~0.5 m/s at 5V"},{"key":"Plate Material","value":"Acrylic"},{"key":"Wheel Diameter","value":"65 mm"},{"key":"Includes","value":"Acrylic plate, 2 motors, 2 wheels, 1 caster, hardware"}]',
  6, 6, 0, 0, 0, 0, NULL, NULL, 1
),

-- ── 3D Printing ─────────────────────────────────────────────
(
  'PLA Filament 1kg Spool (White, 1.75mm)',
  'pla-filament-1kg-white-1-75mm',
  'Premium quality PLA (Polylactic Acid) filament. Diameter 1.75 mm ±0.02 mm. 1 kg spool. Easy to print, low warping, biodegradable. Suitable for all FDM printers including Ender 3, Prusa, and Bambu Lab. Consistent colour throughout the spool.',
  799.00, 999.00, 20.02, 60,
  'https://picsum.photos/seed/pla-filament-white/600/600',
  '["https://picsum.photos/seed/pla-filament-white/600/600"]',
  '["3d-printing","pla","filament","1.75mm","fdm","ender3"]',
  '[{"key":"Material","value":"PLA (Polylactic Acid)"},{"key":"Diameter","value":"1.75 mm ±0.02 mm"},{"key":"Net Weight","value":"1 kg"},{"key":"Print Temperature","value":"180–220°C"},{"key":"Bed Temperature","value":"20–60°C (optional)"},{"key":"Colour","value":"White"},{"key":"Spool Diameter","value":"200 mm"}]',
  7, 6, 0, 0, 0, 0, NULL, NULL, 1
),

-- ── Tools ────────────────────────────────────────────────────
(
  'Digital Multimeter DT9208A',
  'digital-multimeter-dt9208a',
  'Professional-grade digital multimeter with 3.5-digit LCD, auto-ranging, and data hold function. Measures DC/AC voltage, DC/AC current, resistance, capacitance, frequency, temperature, transistor hFE, and diode. CE safety rated. Comes with test leads, thermocouple, and 9V battery.',
  899.00, 1299.00, 30.79, 75,
  'https://picsum.photos/seed/multimeter-dt9208a/600/600',
  '["https://picsum.photos/seed/multimeter-dt9208a/600/600"]',
  '["multimeter","tools","voltage","current","resistance","measurement"]',
  '[{"key":"Display","value":"3.5-digit LCD (2000 count)"},{"key":"DC Voltage","value":"200mV–1000V"},{"key":"AC Voltage","value":"200V–750V"},{"key":"DC Current","value":"2mA–10A"},{"key":"Resistance","value":"200Ω–200MΩ"},{"key":"Capacitance","value":"2nF–200µF"},{"key":"Temperature","value":"-40°C to +1000°C (K-type)"},{"key":"Features","value":"Auto range, Data hold, hFE, Diode test"}]',
  8, 6, 1, 0, 0, 0, NULL, NULL, 1
),
(
  '60W Adjustable Soldering Iron Station',
  '60w-soldering-iron-station',
  '60-watt temperature-controlled soldering iron station. Temperature range 200–480°C with LCD display. Fast warm-up (30 seconds to 300°C). Comes with iron holder with brass-wool tip cleaner, 5 replacement tips, solder, and tip-tinner paste. Ideal for SMD and through-hole work.',
  699.00, 999.00, 30.03, 50,
  'https://picsum.photos/seed/soldering-iron-station/600/600',
  '["https://picsum.photos/seed/soldering-iron-station/600/600"]',
  '["soldering","tools","iron","station","smd","electronics"]',
  '[{"key":"Power","value":"60W"},{"key":"Temperature Range","value":"200–480°C"},{"key":"Display","value":"LCD digital"},{"key":"Warm-up Time","value":"~30 s to 300°C"},{"key":"Tip Type","value":"900M series"},{"key":"Included Tips","value":"5 assorted"},{"key":"Includes","value":"Iron holder, tip cleaner, solder, tip-tinner"}]',
  8, 6, 0, 0, 0, 0, NULL, NULL, 1
),

-- ── Cables & Connectors ──────────────────────────────────────
(
  'Jumper Wire Kit — 120 Pieces (M-M/M-F/F-F)',
  'jumper-wire-kit-120pcs',
  'Assorted jumper wire set with 40 Male-to-Male, 40 Male-to-Female, and 40 Female-to-Female wires. Each 20 cm long. Flexible, colour-coded, and breadboard-friendly. Essential for prototyping with Arduino, ESP32, and Raspberry Pi.',
  149.00, 199.00, 25.12, 2000,
  'https://picsum.photos/seed/jumper-wires-kit/600/600',
  '["https://picsum.photos/seed/jumper-wires-kit/600/600"]',
  '["jumper-wires","breadboard","connector","prototyping","kit"]',
  '[{"key":"Total Pieces","value":"120 (40×M-M, 40×M-F, 40×F-F)"},{"key":"Length","value":"20 cm each"},{"key":"Wire Type","value":"26 AWG, flexible"},{"key":"Colours","value":"10 colours × 4 per type"},{"key":"Connector","value":"2.54 mm pitch"},{"key":"Max Voltage","value":"300V"},{"key":"Max Current","value":"1A"}]',
  9, 6, 0, 0, 1, 0, NULL, NULL, 1
),
(
  'USB Type-C Cable Pack — 1m, 3 Cables',
  'usb-type-c-cable-pack-3pcs',
  'Pack of 3 high-quality USB-A to USB-C cables. 1 metre length. Supports USB 2.0 speeds (480 Mbps) and up to 3A fast charging. Braided nylon jacket for durability. Compatible with Arduino Nano 33, ESP32-S3, Raspberry Pi 5, and any USB-C device.',
  299.00, 449.00, 33.40, 500,
  'https://picsum.photos/seed/usb-c-cable-pack/600/600',
  '["https://picsum.photos/seed/usb-c-cable-pack/600/600"]',
  '["usb-c","cable","charging","data","braided","3-pack"]',
  '[{"key":"Length","value":"1 metre"},{"key":"Quantity","value":"3 cables"},{"key":"Connector A","value":"USB-A 2.0"},{"key":"Connector B","value":"USB-C"},{"key":"Data Speed","value":"480 Mbps (USB 2.0)"},{"key":"Max Charge Current","value":"3A"},{"key":"Jacket","value":"Braided nylon"}]',
  9, 6, 0, 0, 0, 0, NULL, NULL, 1
),

-- ── Kits ─────────────────────────────────────────────────────
(
  'Arduino Ultimate Starter Kit — 32 Components',
  'arduino-ultimate-starter-kit',
  'Everything you need to start learning electronics with Arduino. Includes an Arduino Uno R3 clone, breadboard, 32 components (LEDs, resistors, sensors, servo, LCD, buzzer, motors, relay, and more), and 30+ project PDFs. Perfect first kit for students, hobbyists, and makers.',
  1499.00, 2299.00, 34.80, 60,
  'https://picsum.photos/seed/arduino-starter-kit/600/600',
  '["https://picsum.photos/seed/arduino-starter-kit/600/600","https://picsum.photos/seed/arduino-starter-kit-b/600/600"]',
  '["kit","arduino","starter","beginner","learning","diy","components"]',
  '[{"key":"Board","value":"Arduino Uno R3 compatible"},{"key":"Breadboard","value":"830-point"},{"key":"Components","value":"32 types (LEDs, resistors, sensors, servo, LCD, buzzer, relay, motors, more)"},{"key":"Jumper Wires","value":"65 pieces"},{"key":"USB Cable","value":"Included"},{"key":"Projects","value":"30+ PDF tutorials"},{"key":"Suitable For","value":"Beginners (no prior experience needed)"}]',
  10, 1, 1, 0, 1, 0, NULL, NULL, 1
);


-- ===========================================================
-- DIY KITS (3)
-- ===========================================================
INSERT INTO `diy_kits`
  (`name`,`description`,`price`,`thumbnail`,`images`,`components`,`difficulty`,`stock`)
VALUES
(
  'Arduino IoT Weather Station Kit',
  'Build a fully functional IoT weather station that logs temperature, humidity, and air pressure to the cloud. Displays live data on an OLED screen and sends WhatsApp alerts when values go out of range. Complete step-by-step video tutorial and PCB included.',
  1299.00,
  'https://picsum.photos/seed/iot-weather-kit/600/600',
  '["https://picsum.photos/seed/iot-weather-kit/600/600","https://picsum.photos/seed/iot-weather-kit-b/600/600"]',
  '[{"name":"ESP32 DevKit V1","qty":1},{"name":"DHT22 Temperature & Humidity Sensor","qty":1},{"name":"BMP280 Barometric Pressure Sensor","qty":1},{"name":"0.96 inch OLED Display (I2C)","qty":1},{"name":"830-point Breadboard","qty":1},{"name":"Jumper Wires (40 pcs)","qty":1},{"name":"USB-C Cable","qty":1},{"name":"Custom PCB","qty":1},{"name":"3D Printed Enclosure","qty":1},{"name":"Project PDF Guide","qty":1}]',
  'beginner',
  40
),
(
  'Raspberry Pi Security Camera Kit',
  'Build a smart home security camera with Raspberry Pi Pico W and an OV2640 camera module. Streams live video over WiFi to your browser. Motion detection triggers a Telegram alert with snapshot. Includes 3D-printed dome enclosure, IR LED board, and complete Python source code.',
  2499.00,
  'https://picsum.photos/seed/security-camera-kit/600/600',
  '["https://picsum.photos/seed/security-camera-kit/600/600","https://picsum.photos/seed/security-camera-kit-b/600/600"]',
  '[{"name":"Raspberry Pi Pico W","qty":1},{"name":"OV2640 Camera Module","qty":1},{"name":"PIR Motion Sensor","qty":1},{"name":"IR LED Board (850nm)","qty":1},{"name":"5V 3A USB-C Power Supply","qty":1},{"name":"MicroSD Card (16GB)","qty":1},{"name":"3D Printed Dome Enclosure","qty":1},{"name":"M2 Mounting Hardware","qty":1},{"name":"MicroUSB Cable","qty":1},{"name":"Python Source Code (USB)","qty":1}]',
  'intermediate',
  25
),
(
  'Line-Following Robot Car Kit',
  'Build a robot car that autonomously follows a black line on a white surface. Uses 5-sensor IR array and PID control for smooth, accurate tracking. Includes fully assembled chassis, motor driver, display, and pre-written Arduino code. Expand with Bluetooth control and smartphone app.',
  1799.00,
  'https://picsum.photos/seed/line-follower-robot-kit/600/600',
  '["https://picsum.photos/seed/line-follower-robot-kit/600/600","https://picsum.photos/seed/line-follower-robot-kit-b/600/600"]',
  '[{"name":"Arduino Nano Every","qty":1},{"name":"L298N Motor Driver","qty":1},{"name":"2WD Robot Car Chassis","qty":1},{"name":"5-Channel IR Sensor Array","qty":1},{"name":"HC-05 Bluetooth Module","qty":1},{"name":"0.96 inch OLED Display","qty":1},{"name":"18650 Battery Holder (2-cell)","qty":1},{"name":"18650 Li-Ion Batteries","qty":2},{"name":"Line-Following Track Sheet (A0)","qty":1},{"name":"Pre-loaded Code + App APK","qty":1}]',
  'intermediate',
  30
);


-- ===========================================================
-- BLOG POSTS (3)
-- ===========================================================
INSERT INTO `blogs`
  (`title`,`slug`,`excerpt`,`content`,`thumbnail`,`author_name`,`category`,`tags`,`reading_time`,`view_count`)
VALUES
(
  'Getting Started with ESP32: Your First IoT Project',
  'getting-started-esp32-first-iot-project',
  'The ESP32 is arguably the best value development board you can buy in 2026. In this guide we walk you through setting up the Arduino IDE, connecting to WiFi, and building a simple temperature dashboard that you can check from your phone.',
  '<h2>Why ESP32?</h2>
<p>The ESP32 packs a dual-core 240 MHz Xtensa LX6 processor, 520 KB SRAM, WiFi, Bluetooth, 38 GPIO pins, and two 12-bit DACs — all for under ₹450. For makers in India, it is the most cost-effective path to connected hardware.</p>
<h2>What You Need</h2>
<ul>
<li>ESP32 DevKit V1</li>
<li>DHT22 sensor</li>
<li>Jumper wires and breadboard</li>
<li>Micro-USB cable</li>
<li>Arduino IDE 2.x with ESP32 board package installed</li>
</ul>
<h2>Step 1 — Install the Board Package</h2>
<p>In Arduino IDE 2, go to <strong>File → Preferences</strong> and add this URL to <em>Additional Board Manager URLs</em>:</p>
<pre><code>https://raw.githubusercontent.com/espressif/arduino-esp32/gh-pages/package_esp32_index.json</code></pre>
<p>Then open <strong>Tools → Board → Board Manager</strong>, search for <em>esp32</em> by Espressif, and install version 3.x.</p>
<h2>Step 2 — Wire the DHT22</h2>
<p>Connect DHT22 VCC → 3.3V, GND → GND, and DATA → GPIO4 through a 10kΩ pull-up resistor to 3.3V.</p>
<h2>Step 3 — Read Temperature and Push to a Dashboard</h2>
<p>Install the <code>DHT sensor library</code> by Adafruit and <code>WiFi</code> (built-in). The sketch below reads the sensor every 10 seconds and posts the value to a free ThingSpeak channel:</p>
<pre><code>#include &lt;WiFi.h&gt;
#include &lt;DHT.h&gt;

#define DHTPIN 4
#define DHTTYPE DHT22
DHT dht(DHTPIN, DHTTYPE);

const char* ssid     = "YourWiFi";
const char* password = "YourPassword";
const char* host     = "api.thingspeak.com";
const char* apiKey   = "YOUR_WRITE_KEY";

void setup() {
  Serial.begin(115200);
  dht.begin();
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) delay(500);
  Serial.println("WiFi connected");
}

void loop() {
  float t = dht.readTemperature();
  float h = dht.readHumidity();
  if (!isnan(t)) {
    WiFiClient client;
    if (client.connect(host, 80)) {
      String url = "/update?api_key=" + String(apiKey)
                 + "&field1=" + String(t)
                 + "&field2=" + String(h);
      client.print("GET " + url + " HTTP/1.1\r\nHost: " + host + "\r\nConnection: close\r\n\r\n");
    }
  }
  delay(10000);
}</code></pre>
<h2>Next Steps</h2>
<p>Once your data is flowing to ThingSpeak, try adding an OLED display to show readings locally, or use the ESP32 BLE stack to stream data to your phone. The ESP32 is the perfect canvas for any IoT idea — the only limit is your imagination.</p>',
  'https://picsum.photos/seed/esp32-blog-post/1200/630',
  'Tejas Sharma',
  'Tutorial',
  '["esp32","iot","wifi","dht22","arduino","tutorial","beginner"]',
  7, 312
),
(
  'Top 5 Sensors Every Maker Should Own in 2026',
  'top-5-sensors-every-maker-should-own-2026',
  'Whether you are building your first weather station or a complex robot, the right sensor makes all the difference. We picked the 5 most versatile, affordable sensors available in India — with code examples and project ideas for each.',
  '<h2>1. DHT22 — Temperature & Humidity</h2>
<p>The DHT22 is more accurate than its cheaper cousin DHT11 (±0.5°C vs ±2°C). It is calibrated from the factory and needs just one GPIO pin. At ₹199 it is the best climate sensor per rupee. Use it for weather stations, plant monitors, server room alarms, and HVAC control.</p>
<h2>2. HC-SR04 — Ultrasonic Distance</h2>
<p>Two ping transducers, 2 cm to 4 m range, 3 mm accuracy. Trigger a 10 µs pulse, listen for the echo, and divide by 58 to get centimetres. Used in parking sensors, bin-level monitors, and robot obstacle detection. Costs under ₹100.</p>
<h2>3. MPU-6050 — 6-Axis IMU</h2>
<p>Accelerometer + gyroscope in a tiny DFN package. Communicates over I2C with an onboard DMP (Digital Motion Processor) that computes pitch, roll, and yaw so your microcontroller does not have to. Essential for drones, balancing robots, and motion-controlled displays.</p>
<h2>4. MQ-135 — Air Quality</h2>
<p>Detects CO2, NH3, benzene, and smoke. The analog output gives a voltage proportional to concentration. Combine it with an ESP32 and a 0.96" OLED for a pocket air quality badge. Recalibrate every 24 hours in clean air for best accuracy.</p>
<h2>5. Soil Moisture Sensor</h2>
<p>Two resistive probes measure the conductivity of soil. When resistance drops (wet soil) the analog output rises. Pair it with a relay, a solenoid valve, and an ESP32 to build an automatic irrigation system. Perfect for balconies, greenhouses, and terrace gardens.</p>
<h2>Where to Buy</h2>
<p>All five sensors are available individually or as part of our <strong>Arduino Ultimate Starter Kit</strong> at TT Electro — with free shipping on orders above ₹599.</p>',
  'https://picsum.photos/seed/sensors-blog-post/1200/630',
  'Priya Nair',
  'Buying Guide',
  '["sensors","dht22","hcsr04","mpu6050","mq135","maker","buying-guide"]',
  5, 487
),
(
  'How to Design and Print Your First 3D Part: A Beginner Guide',
  'design-print-first-3d-part-beginner-guide',
  'Going from idea to physical object in just a few hours is one of the most satisfying experiences in making. This guide covers the whole workflow — from sketching in FreeCAD to slicing in Cura to using TT Electro''s 3D printing service if you do not own a printer yet.',
  '<h2>Step 1 — Design Your Part</h2>
<p>Start with <strong>FreeCAD</strong> (free, open source) or <strong>Fusion 360</strong> (free for hobbyists). For simple enclosures and brackets, FreeCAD Sketcher + Part Design is all you need. Model your part, export as <code>.STL</code>.</p>
<p>Key design rules for FDM printing:</p>
<ul>
<li>Minimum wall thickness: 1.2 mm (2× nozzle diameter)</li>
<li>Avoid overhangs beyond 45° without supports</li>
<li>Add 0.2 mm clearance between mating parts</li>
<li>Orient the part so the strongest layer direction carries the load</li>
</ul>
<h2>Step 2 — Choose Your Material</h2>
<p><strong>PLA</strong> is the easiest to print and best for display models, enclosures, and anything indoors below 50°C. <strong>PETG</strong> adds moisture and chemical resistance — great for food-safe containers and outdoor use. <strong>ABS</strong> tolerates higher temperatures but warps easily and needs an enclosure.</p>
<h2>Step 3 — Slice It</h2>
<p>Open your STL in <strong>Ultimaker Cura</strong>. Recommended settings for PLA:</p>
<ul>
<li>Layer height: 0.2 mm</li>
<li>Infill: 15% for display parts, 40% for structural</li>
<li>Print speed: 50 mm/s</li>
<li>Supports: auto, touching build plate only</li>
</ul>
<p>Export the G-code and print — or send us the STL!</p>
<h2>Step 4 — Use Our 3D Printing Service</h2>
<p>No printer? No problem. Upload your STL on TT Electro''s <strong>3D Printing Service</strong> page, choose material (PLA, PETG, ABS, TPU), quantity, and colour. We will send you a quote within 4 hours. Typical lead time is 2–3 business days.</p>',
  'https://picsum.photos/seed/3dprinting-blog-post/1200/630',
  'Rohan Mehta',
  'Tutorial',
  '["3d-printing","freecad","cura","pla","petg","beginner","guide"]',
  8, 218
);


-- ===========================================================
-- OFFERS (3 active promotions)
-- ===========================================================
INSERT INTO `offers`
  (`title`,`description`,`type`,`discount`,`ends_at`,`badge`)
VALUES
(
  'Flash Sale — Sensors Up to 40% Off',
  'Limited-time flash sale on our most popular sensor modules. DHT22, MPU-6050, and NRF24L01 at slashed prices. Sale ends Sunday midnight. No coupon needed — prices are already reduced.',
  'flash',
  '40%',
  '2026-06-22 23:59:59',
  'Flash Sale'
),
(
  'First Order 10% Off with TTFIRST',
  'New to TT Electro? Use coupon code TTFIRST at checkout to get 10% off your first order (up to ₹500 discount). Valid on orders above ₹299. One use per customer.',
  'coupon',
  '10%',
  NULL,
  'New Customer'
),
(
  'Maker Bundle Deal — Spend ₹999+ Save ₹200',
  'Building something big? Use code MAKER20 on orders above ₹999 to save 20% (up to ₹800). Perfect when you are stocking up on sensors, modules, and that starter kit you have been eyeing.',
  'coupon',
  '20%',
  NULL,
  'Maker Deal'
);


-- ===========================================================
-- SAMPLE REVIEWS (for featured products)
-- Note: user_id=2 (admin) is used as author for demo reviews.
--       Replace with real user reviews after launch.
-- ===========================================================
-- We need product IDs — use a safe pattern that works even if
-- IDs shift. SELECT then INSERT is safest. Use stored procedure
-- or just target the first few products inserted above.
-- Since this is inserted fresh after schema.sql, products start at id=1.

INSERT INTO `reviews` (`product_id`,`user_id`,`rating`,`title`,`body`) VALUES
(1, 2, 5, 'Exactly as described', 'My Arduino Uno R3 arrived well packed and worked out of the box. Tested with the blink example and all 14 digital pins checked out. Great quality for the price.'),
(1, 2, 4, 'Good board, minor delay', 'Solid Uno clone. Took 6 days to reach Hyderabad but the board itself is perfect. Loaded several sketches without issues.'),
(3, 2, 5, 'Best ESP32 deal on the market', 'The ESP32 DevKit V1 connected to my router in 2 seconds. Running a web server and MQTT client simultaneously with no stability issues after 3 weeks of uptime.'),
(3, 2, 5, 'WiFi + BLE at this price is unreal', 'I was using NodeMCU before this. The dual-core and larger RAM make a huge difference for FreeRTOS projects. Highly recommend.'),
(4, 2, 5, 'Pico W is a game-changer', 'MicroPython runs beautifully on the Pico W. Set up an HTTP sensor dashboard in under an hour. The 26 GPIO pins and WiFi at this price point is incredible.'),
(6, 2, 5, 'Most reliable ultrasonic sensor', 'Tested 10 units for a batch robotics project — all within 3 mm accuracy across the 2–300 cm range. Excellent consistency.'),
(9, 2, 5, 'OLED looks amazing on my project enclosure', 'Crystal clear display even in bright sunlight. I2C address was 0x3C as advertised. Works perfectly with Adafruit SSD1306 library.'),
(14, 2, 5, 'HC-05 paired with my phone in 10 seconds', 'Changed baud rate to 115200 via AT commands without any trouble. Running a Bluetooth serial control for my rover now.'),
(20, 2, 4, 'Solid motor driver, runs a little warm', 'Drives two 12V motors smoothly. Add a heat sink if you are running near 2A — it gets warm but that is normal for L298N. Great value.'),
(25, 2, 5, 'Perfect first kit, zero missing parts', 'Everything was bagged and labelled. Completed 8 projects in the first week following the PDF tutorials. My 14-year-old nephew is hooked on electronics now!');


-- ===========================================================
-- Done!
-- 25 products | 3 DIY kits | 3 blog posts | 3 offers | 10 reviews
-- ===========================================================
