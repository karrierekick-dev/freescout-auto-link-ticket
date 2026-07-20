# About
This [FreeScout](https://freescout.net) module recognizes ticket numbers like #1234 and links them to the corresponding tickets.

# Requirements
* Existing [FreeScout](https://freescout.net) installation

# Installation
You may [install this module like any other FreeScout module](https://github.com/freescout-helpdesk/freescout/wiki/FreeScout-Modules#2-installing-official-modules).

Go to the "src" directory of this repository and copy the folder "AutoLinkTicket" into your "Modules" folder of your [FreeScout](https://freescout.net) installation.

Go to the "Modules" section in FreeScout and activate "Auto Link Ticket"

# Updates
From version 1.1.0 onwards FreeScout can update this module via the Modules page (`latestVersionUrl` / `latestVersionZipUrl` in `module.json`).

If you still run 1.0, install 1.1.0 once manually (copy `AutoLinkTicket` into `Modules/` or use the release ZIP). After that, in-app updates work.

Release ZIPs: https://github.com/karrierekick-dev/freescout-auto-link-ticket/releases

# Features
* Links `#1234` in conversation threads to the matching ticket
* Works with custom conversation numbers (`Settings → General → Conversation Number`)
* Skips CSS colors (`#333333`) and HTML entities (`&#252;`) so umlauts/Cyrillic stay intact
* Opens ticket links in the same tab (`target="_self"`)
