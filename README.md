# Dev-Team-Dashboard

This was originally a quick hack job. A web application I whipped up in a couple hours to help manage our uni project team.
No planning or programming style was used as I just wanted something we could use as quickly as possible.

But recently I decided to update it using class objects instead of just the random mish mash of php and sql. There are some old unsightly bits of code left over here and that I didnt get arround to changing.

But now it has classobjects generated by code from the database tables it finds along with my extention of MySQLI to update or save new records just by passing a class object to it. It's still a little bit of a mashup up but detects class object types and mapps them to the database correctly.

Intended Features
=================
Multiple Projects (DONE)
Would be nice if the dashboard could keep jobs separated by multiple projects and be associated with team members that work on the project.

Multiple Releases per Project. (DONE)
Would be nice if the projects current list of jobs could be achieved into a release version and start clean with only jobs that were not completed being carried over into the next release.

Documentation for each project.
Would be nice if the dashboard provided a process for starting a new project and initializing document templates for the Design/ Requirements, RTM etc with links to google docs or some other cloud service.

Timesheet system.
Would be nice if team members in the system can log their hours and associate specific jobs to those hours. This should be designed in such a way that the system could be used as a guide for quoting jobs to clients.

Reference materials for common code and math/physics principles.
Be nice to have the ability to build a library code or mathematical formulas etc for team members to be able to quickly reference. This should be designed in such a way that the library can be searched for keywords quickly to display relevant formulas or reference links quickly.

Discontinued.
=============
The dashboard is usable witch is enough and I dont have time to add features or make it more presentable now.
I have made it public as an example of my old work for people to look at.
Had to make a new repo to clean out sensitive info in the commit history.