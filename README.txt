

    How do I install WebTools?
    Just upload the files to your web server directory and give the web server user read/write permissions on the "ratelimit" directory. If you want to change some of the options, open up "config.php" and use the comments or the following section as a guide.
    What variables can I add in config.php?
        $config['ratelimit'] = 5; - The amount of time, in seconds, before an IP can make another request.
        $config['showerrors'] = TRUE; - Should the script output errors to the browser if they occur? Set to FALSE for production environments, TRUE for development environments.
        $config['dieonerror'] = FALSE; - Should the script die() if ANY error or warning occurs?
        $config['limitfolder'] = './ratelimit/'; - The folder in which rate limit information is stored.
        $config['seourls'] = FALSE; - Set this to TRUE if you have enabled SEO URLs through .htaccess, otherwise, leave it as FALSE.
        $config['template'] = './template.html'; - The location of the page template.
        $config['pagefolder'] = './pages/'; - The location of the directory used to store pages with a trailing slash. Don't change this unless you know what you're doing.
        $config['homepage'] = 'home'; - The name of the module used to handle requests that don't specify a page.
        $config['404page'] = '404'; - The name of the page used to handle requests to non-existing pages.
        $config['serverlist'] = './whois-servers.txt'; - The location of the whois-servers.txt file, relative to index.php.
        $config['maxredirs'] = 1; - The maximum number of WHOIS server redirects to accept before sticking with a server. The recommended value is one. Only increase this if the WHOIS page frequently returns irrelevant results, but be warned that it can impede system performance. Setting this value to less than one will cause .com, .net, and other "thin client" domains to return only very vague information. 
    How do I customize the template?
    The template for WebTools output is, by default, stored in "template.html". You can change the location using "config.php". In your template file, you can include the following strings, which will be dynamically replaced with information from the script:
        %content% - This is the most important variable. It contains all of the output for the script, like forms, WHOIS information, and more.
        %generationtime% - The amount of time (in seconds) that it took to generate the page.
        %year% - The current four-digit year. Useful for copyright statements and the like.
        %serverlist% - The relative URL to the list of servers used in the script.
        %page% - A string that refers to the current page.
        %basehref% - An href appropriate for use in links that sends you to the main page of this script (i.e. /folder/index.php)
        %pagehref% - A href like %basehref% that can be used for linking back to the current page.
    If you know how to work with PHP, you can add more strings by editing in index.php. Otherwise, you can contact me for support via e-mail at zbloomq@live.com.
    What is "whois-servers.txt"?
    "whois-servers.txt" contains the list of WHOIS servers used in the script for looking up information. You can edit it to reorder domains in the list or to remove them. A current version of "whois-servers.txt", containing the most recent list of WHOIS servers available on the Internet, can be found at: http:-nirsoft.net/whois-servers.txt
    I have another question. How can I contact you?
    Send me an e-mail at zbloomq@live.com and I will do my best to respond as quickly as possible. I will gladly help integrate the script into your existing site for no additional charge.



