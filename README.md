# **Mojo-Scribe** #

## API Documentation : ##

### 1. Upload Post API ###
The Upload post API is to be called while uploading the post from the device on to the server. As per the requirements the following fields are mandatory for uploading a post:
Following are the fields along with the keys used:

a. Media upload/file.

b. Headline/title  (headline)

c. Category  (category)

d. Impact  (impact)

e. Anonymous/Non-anonymous(user)

For the anonymous post, the device must send a field with the type of user i.e. either anonymous/self.

Although the API would give a success response if only these fields are set, it is recommended that all the fields in the upload section be set.

Other fields in the API along with the keys:

f. Description of the Post  (description)

g. Hashtags  (Hashtags are in the array with key "hashtags")

h. Media Source

 Although this is not a mandatory field the user can set the field as either "self" or specify the source that he has taken the media from. The key is "source".