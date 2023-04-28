# Pars

 [ورژن فارسی](https://github.com/amirhosseinchoghaei/Pars/blob/main/readme2.md)


Auto Call Voice Sender For Astersik Issabel and Elastix
if you want send a voice to number and play it automaticaly isntall this script with ssh remote on your issabell and elastix 

Attention : You should Have a Sip Trunk Number To Use This Script... if you have a gateway analog to digital it not working correctly Just Use Sip Trunk For outgoing calls . For Local Extentions you don't have problem.


# Install Service : ssh remote on issabel or elastix

```
wget https://raw.githubusercontent.com/amirhosseinchoghaei/Pars/main/pars.sh && chmod 777 pars.sh && sh pars.sh

```
Attention : First Be Sure What is your Mysql Password ! if You enter the Wrong Password Script Can't Work

For Example Befor You Start the installation script Enter This Command : mysql -uroot -p 

then enter the password and see it is correct or not. !

# How to Use ?

open this URL **Without https** [http://issabel_or_elastix ip]/pars

![This is an image](https://raw.githubusercontent.com/amirhosseinchoghaei/Pars/main/ISSABEL-Auto-Dialler.jpg)

# HELP

1- Wait Time : Wait for User to Accept Call .(seconds)

2- Interval : Space Between each Calls (seconds) Depended on How Many Channels Support your Sip Trunk (multi calls)

3- Caller ID : it's local caller id not matter What you Set ... !

4- Prefix : Use for Outbound .

5- Press Number 1 or 2 : if user press 1 or 2 during voice massage will be redirect to extension that you set

6- Upload Number : Download Example File and import your numbers with voice name

7- History : Show Who accepted call or not in excel

8- Manage Voice : Upload Voice With Wav or Mp3 8000HZ , 16Bit , Mono

9- api : you can use url api for example : http://[issabelip]/pars/api.php?action=democall&phone=[phone_number]&file=[audio_name.wav]&action=call  or 
http://192.168.1.150/pars/api.php?action=democall&phone=91999&file=welcome.wav&action=call


# Automatic Dialer Features

- PHP script structure
- Being open source
- Pitching at a given time
- Scheduled calls With API
- API capability and connection
- Simple and easy to use
- Compatible with Asterisk (Elastix, Issabel)
- IVR scenario definition
- Monitoring of ongoing calls
- Ability to report successful and unsuccessful calls
- The possibility of multiple simultaneous calls with different messages
- You can distinguish between active and inactive numbers


>> Made With Love By : AmirHossein Choghaei
