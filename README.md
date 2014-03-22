<h1>PHP User Messaging system:</h1>
PHP multi user multi conversations messaging system, just like facebook :)

<h2>Description:</h2>
I decided to create this package because of the lack of real good messaging systems in php with the proper features like, deleting archiving messages for each specific user, multipile users in conversations and more.

I am trying to build this in a very generic way so it could be easilly implemented for each framework or project.
Currently I'll try to build with laravel and eloquent as this is the project I'm currently building.

<h3>Would be happy to get help and ideas on the bad practices I do :)</h3>

<h2>Features:</h2>
<ul>
<li>Message status for each user: deleted, read, unread, archived</li>
<li>multipile conversations for each user</li>
<li>each conversation has 2 or more users</li>
<li>ability to have 1 unique conversation for each 2 users</li>
<li>Get all users conversations with last message in conversation</li>
<li>get all conversations messages for a specific user</li>
</ul>


<h2>How is it built?</h2>
this are tables needed:

users: <b>id</b>

messages: <b>id</b>, sender_id, conv_id, content, updated_at, created_at

messages_status: <b>id</b>, user_id, msg_id, self, status

conversations: <b>id</b>, updated_at, created_at

conv_users: <b>conv_id</b>, <b>user_id</b>



<h5>users</h5>
simply hold the users that use the messages

<h5>messages</h5>
contains the message itself, who sent it, to which conversation, the content and the dates.

<h5>messages_status</h5>
this is the "magic" it has a row for each user that got a message in a specific conversation. IE if I send a message to conversation with 4 users, we will have 5 rows in this table, the sender and the other 4 recipents, it will contains the specific user message status, as is it deleted, read, unread...

<h5>conversations</h5>
currently only has an id, and dates

<h5>conv_users</h5>
holds the users in conversation relationships 
