# Bluum App

This is the basic React-Native Structure for the Bluum Inpatient Care Companion App, using primarilly TypeScript.

## QuickStart Guide

Hey! If you're reading this, you're probably trying to get the app to launch. Here's how you can do it!

1. If you're viewing the ENTIRE project (including Laravel), make sure to run `cd BluumApp` in your terminal.
2. Run `npm i` to download all dependencies for the project.
3. To launch the app on your *desktop*, all you need to do is run the "Start" command from the package.json file. (`expo start`)
    * If you want to run it from a mobile device, download the "Expo Go" app and scan the QR code that appears when you run the "Start" script. 
4. If you want to run on a phone/tablet emulator on your local machine, download Android studio [Here](https://developer.android.com/studio)
    * Note: Utilizing Expo Go is *MUCH* easier, and requires less processing power on your PC.
5. The app should be running!

### Ev, it's broken!
If the app breaks (which it very well could during this testing phase), don't panic! It can probably be fixed with one of these quick fixes:
1. `Attempted to Navigate` Error
    * If you're seeing this error (`Attempted to navigate before mounting the Root Layout component. Ensure the Root Layout component is rendering a Slot, or other navigator on the first render.
`), it's most likely because you refreshed the app. This is an issue with the routing on desktop devices, but will *NOT* effect the final app. For peace of mind, though, I am currently working on a fix to this, and will update the README when this is no longer an issue.
2. Styling / Content / Scripts Aren't Updating.
    * React Native LOVES to do this. Sometimes, for no reason at all, things just... *don't* update. Luckilly, the fix is really simple: Stop the current expo script and run the "fresh-start" command. This will fix those issues 90% of the time.

If those tips don't work, don't hesitate to reach out! 
