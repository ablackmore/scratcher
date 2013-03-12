Scratcher Project

The current scratcher program allows a user to scratch the three squares and if the picture is the same on the three squares the user receives a winner message, otherwise the user receives the loser response. 
The scratcher game was built and designed to work on a tablet device as well as a desktop.

A simple MySQL database needs to be built to house users and store different configurations of the scratcher game to allow a user to save different configurations of the game. 
The scratcher needs to have management there will need to be a simple user control system requiring a username and password to access the ability to configure a scratcher. 
The user once logged in should be able to see the variations they have created and add new variations of the game.

The current game has 9 tiles, 4 prizes, 3 attempts and 3 matches. In order to win all 3 of your attempts must be the same image. 
Each of these fields needs to be changed so that it will be user defined and not static. 
A user should be able to create a version that only has 1 tile, 1 prize, 1 attempt and 1 match or as many of each of those fields as they would like. 
The trick here is that some logic will need to be implemented so that the user cannot create a game that is unwinnable (ex. 6 tiles, 1 prize, 2 attempts, 3 matches would be unwinnable because you only get 2 scratches but have to match 3 tiles).
The different versions of the game should provide a unique URL to reach the game and be displayed in the management portion where the user manages their different versions of the game.
