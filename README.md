# IPTCgallery
Web gallery displaying IPTC metadata as captions.

## Setup
Upload to web server using FTP.
Every gallery is in a folder. There is a folder “new-gallery” that serves as a template for new galleries. 
To add a gallery, duplicate the whole folder “new gallery” and give it a name to your liking, e.g. “sports-portfolio”. 
Do not change anything else, do not rename the contents of the folder.

Prepare your photos for upload, e.g. with PhotoMechanic 
- Recommended fixed aspect ratio 1280px wide JPGs
- IPTC data is used for captions: title, headline, description/caption
- Upload photos to folder “img” in the created folder “sports-portfolio”. Do not rename the folder “img”.
- The server creates thumbnails for every image. The thumbnails are stored in a folder named “t_img”.

There is a file named ‘index.html’ in the top level directory. This is the file showing the thumbs of your individual galleries. 
To add galleries to the homepage, edit this file's HTML. 
- Specify the URL of your individual galleries. 
- Specify the URL of a thumbnail in the folder “t_img”.
