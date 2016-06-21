/* * * * * * *
 * Sideboard *
 * * * * * * /

/*
 * Populate Card Table: http://sideboard.io/staging/cms/scripts/card-database-upload/populate-card-table.php?p=f84dbadc42d0447cb94e5774a1c46b7c
/*

/*
 * Installing and using Vagrant to set up sideboard repository to run in local environment ( via terminal ):

 *    => head over to google and type in 'install vagrant'

 *    => download necessary install for your OS

 *    => download Oracle VirtualBox if you do not already have it installed ( must be oracle virtualbox )

 *    => head to the WEB/IT folder in the sideboard directory in the google drive to extract the 'vagrant' file ( necessary to run the script/provisions )

 *    => store the vagrant file in any directory ( preferably the desktop ), rename it sideboard and access it via terminal ( command: cd ~/Desktop/sideboard )

 *    => create a folder called 'www' within the newly created sideboard directory ( command: mkdir www )

 *    => run the command 'ls' to ensure you are in the directory that you can view the 'www' folder

 *    => run the command 'vagrant up' ( this will start the virtual machine with the vagrantfile provisions you pulled from the google drive )

 *    => once your environment is up ( wait time: est. 5-8 mins ), run the command 'ls' to ensure that you are still in the same directory that displays the www folder

 *    => remove the folder ( command: rm -rf www ) to remove the wordpress installation

 *    => run the command 'ls' to ensure you are still in the sideboard directory ( vagrant directory ), however this time you should see no www folder

 *    => head over to bitbucket.org and clone the sideboard repository ( click the three dots under garbage can on top left just under bitbucket logo )

 *    => copy and paste the code ( git clone ****.git ) that bitbucket gives you into the terminal

 *    => add this to the end of the above command ' www' ( the command should look something like this 'git clone ****.git www' ) and run the command

 *    => run the command 'ls' to ensure that the 'www' folder was successfully created

 *    => run the commands 'cd www' to change directory and then 'ls' to check to see if the repository was cloned successfully

 *    => if everything is as it should be, run the command 'vagrant reload' to restart the environment with the adjusted folders

 *    => once the environment is up and running, head to browser and type in localhost:8080

 *    => enjoy your local environment!

 *    => TIP: three helpful vagrant commands:

 *        => 'vagrant up' : starts up local environment

 *        => 'vagrant halt' : powers down local environment

 *        => 'vagrant reload' : restarts local environment

 *        => TIP: all three commands must be run in the directory that DISPLAYS the www folder

/*

/*

 * Atom.io Install:

 *    => head to google and type install atom.io

 *    => download necessary install for your OS

 *
 * Atom.io Settings:

 *    => Press 'cmd' + ',' for Mac, 'ctrl' + ',' for Windows

 *    => Show Indent Guide ( checked )

 *    => Show Invisibles ( checked )

 *    => Show Line Numbers ( checked )

 *    => Toggle Soft Wrap

 *

 * Atom.io Packages/Themes:

 *    => Press 'cmd' + ',' for Mac, 'ctrl' + ',' for Windows

 *    => go to install tab

 *    => Package: Highlight Line ( highlights line, can edit colors of border )

 *    => Package: Line Ending Converter ( Use Unix )

 *    => Package: MiniMap ( similar to Sublime minimap )

 *    => Package: Linter ( you can install these but do not enable them: linter-js, linter-phpcs )

 *    => Theme: Monokai ( Syntax Theme )

/*

/*
 * Node.js and NPM install:

 *    => go to google and type install node.js

 *    => download necessary install for your OS

 *    => this will install npm by default

 *    => once installed run the command 'node -v'

 *    => ensure that a version is being returned ( if not you did not install node correctly )

 *    => run the command 'npm -v'

 *    => ensure that a version is being returned ( if not you did not install node correctly )

*/

/*

 * NPM install:

 *    => npm install gulp / sudo npm install gulp

 *    => npm install ruby

*/

/*
 * Node.js and NPM install:

 *    => go to google and type install node.js

 *    => download necessary install for your OS

 *    => this will install npm by default

 *    => once installed run the command 'node -v'

 *    => ensure that a version is being returned ( if not you did not install node correctly )

 *    => run the command 'npm -v'

 *    => ensure that a version is being returned ( if not you did not install node correctly )

*/

/*

 * NPM install:

 *    => npm install gulp / sudo npm install gulp

 *    => npm install ruby


*/