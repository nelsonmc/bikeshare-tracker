# Bikeshare Tracker
Bikeshare Tracker is a lightweight PHP webapp to find closest stations. While other excellent native iOS apps exist (e.g. Spotcycle), this is an attempt to offer users the most relevant information, even quicker.

Right now it's set up to access Capital Bikeshare data in Washington, DC, but can be easily adapted to other Alta-operated bicycle share programs.

Contributors: nelsonmc

Tags: bikeshare, cabi, bike, share, dc, alta, geolocation


## How it works

This app works using the [HTML5 Geolocation](http://www.w3schools.com/html/html5_geolocation.asp) feature.

If geolocation data is available, an AJAX call accesses the latest Capital Bikeshare XML file (either local cache or remote file) and uses the Haversine distance function to return the closest locations. On click, these locations reveal a map from the current location to the bike.

This app is iOS webapp-friendly, with additional image assets for easy saving to the home screen.


## To do (add support for additional bike share programs)

* [Boston](http://www.thehubway.com/)
* [NYC](http://citibikenyc.com/)
* etc..


## To do (other features)

* Perform distance calculations on the client-side (JS) for reduced server load
* Long term - perhaps integrate other trackable transportation options like [Car2Go](http://www.car2go.com)