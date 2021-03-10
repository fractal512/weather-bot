## Weather by Fractal512 (Viber chatbot)

Viber chatbot displays weather information for a selected city periodically with a selected time interval.

### Preview
![Chatbot preview](/blob/assets/weather-by-fractal512-bot.png?raw=true)

### Development and Production modes

#### Development mode
For development purposes, a custom proxy server was developed (located in the `/pa` project subdirectory). Requests flow in development mode has shown below:

![Development mode](/blob/assets/viber-bot-development-mode.png?raw=true)

#### Production mode
Requests flow in production mode:

![Production mode](/blob/assets/viber-bot-production-mode.png?raw=true)

### Life cycle process
User registration, servicing and unsubscribing process:

![Life cycle process](/blob/assets/viber-bot-lifecycle.png?raw=true)
