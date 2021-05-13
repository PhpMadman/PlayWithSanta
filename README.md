# PlayWithSanta

PWS is an old Lua Script from a DC hub.

This is my 2nd attempt to re-write it to php

### The Lua:
* A score table.
* Insulting table when reaching X score
* !throwsanta - Throws santa in to a big/medium/small hole, gives diffrent score depending on size. Big is small score
* !runover - Try to run over santa. Santa might kill you. Gives score depending who kills who
* !killsanta - Almost same as runover, just more of gore / kill
* !killuser - Try to use santa to kill victim. User or Victim might score / get killed
* All commands show a funny text about how Santa got thrown / killed
* !myscore - Show your score
* !scoreboard - Shows top 10 scores

### The Php Beta - Rewrite 2.1.x - Basic Guidelines

* DB
  * Hit, Miss, kill etc msg tabels
  * A user table to store username, pass, score
  * Deaths / Kills
* Buttons
  * Throwsanta
	* Re-Write - It might be more fun to have multiple buttons
  	* Killsanta / runover button, rewrite? and merge in to ThrowSanta
  	* Killuser - Use Userid? to select victim
* Login
  * Once logged in, personal score should always be shown
* Extra
  *  Add throwspeed
  *  Splatter damage on gory kills
  *  Random NPC to acidently kill or get killed by.

### TODO's
* The game should be on the start page
	* But still in own partial template
* Add page & nav to display top scores
* Regiser Page
	* post / cookie for register
* User class
* Score class ?
* Where should we handle add post processes.
	* Sign in / up uses User class ?
	* The game uses a game class, with a score sub class ?
* Game should still support guest playing, but no score saveing for them
* Make it secure, sanetize input and so on
* Add 404 page for ?p
* Move the sql part from User to DB
* Re-write Mysql DB, I don't like it.
  * tabels should be lowercase
  * password is a keyword and should be changed to something else
* Navbar
  * Display user score
  * Right align logout button
* Try to make a cool logo
* Bug: Navbar don't update on user sign in
* Bugs: PSR is not working
