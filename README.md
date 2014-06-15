Laravel User Messaging system
===============================

PHP multi user multi conversations messaging system, just like facebook :)

Description:
----------------

I decided to create this package because of the lack of real good messaging systems in php with the proper features like, deleting archiving messages for each specific user, multipile users in conversations and more.
I am trying to build this in a very generic way so it could be easilly implemented for each framework or project. Currently I'll try to build with laravel and eloquent as this is the project I'm currently building.

Features:
---------
<ul>
<li>Message status for each user: deleted, read, unread, archived</li>
<li>multipile conversations for each user</li>
<li>each conversation has 2 or more users</li>
<li>ability to have 1 unique conversation for each 2 users</li>
<li>Get all users conversations with last message in conversation</li>
<li>get all conversations messages for a specific user</li>
</ul>


How it is built:
----------------

```php
      users: id

      messages: id, sender_id, conv_id, content, updated_at, created_at

      messages_status: id, user_id, msg_id, self, status

      conversations: id, updated_at, created_at

      conv_users: conv_id, user_id
```
### users
simply hold the users that use the messages

### messages
contains the message itself, who sent it, to which conversation, the content and the dates.

### messages_status
this is the "magic" it has a row for each user that got a message in a specific conversation. IE if I send a message to conversation with 4 users, we will have 5 rows in this table, the sender and the other 4 recipents, it will contains the specific user message status, as is it deleted, read, unread...

### conversations
currently only has an id, and dates

### conv_users
holds the users in conversation relationships 



Installation:
----------------

1. Add this: '"tzookb/tbmsg": "1.*"' to your composer.json file
2. run: "composer update tzookb/tbmsg"
3. Now add the service provider to your app.php file: "'Tzookb\TBMsg\TBMsgServiceProvider'"
4. It would be best if you register the Facade in your app.php file: "'TBMsg' => 'Tzookb\TBMsg\Facade\TBMsg'"
5. run the migrations:  "php artisan migrate --package=tzookb/tbmsg"

How to use it?:
----------------

#### Get User Conversations:

```php
        $convs = TBMsg::getUserConversations($user_id);
```
This will return you a "Illuminate\Support\Collection" of "Tzookb\TBMsg\Entities\Conversation" objects.
And foreach Conversation there, you will have the last message of the conversation, and the participants of the conversation.
Example:
```php
        foreach ( $convs as $conv ) {
        
            $getNumOfParticipants = $conv->getNumOfParticipants();
            $participants = $conv->getAllParticipants();
            
            /* $lastMessage Tzookb\TBMsg\Entities\Message */
            $lastMessage = $conv->getLastMessage();
            
            $senderId = $lastMessage->getSender();
            $content = $lastMessage->getContent();
            $status = $lastMessage->getStatus();
        }
```

#### Get User specific conversation:

```php
        $conv = TBMsg::getConversationMessages($conv_id, $user_id);
```
This will return you a "Tzookb\TBMsg\Entities\Conversation" object.
On the object you could get all messages, all participants, conv_id, and more, simply browse the object itself.
Example:
```php
        foreach ( $conv->getAllMessages() as $msg ) {
            $senderId = $msg->getSender();
            $content = $msg->getContent();
            $status = $msg->getStatus();
        }
```




#### Get the conversation id of a conversation between two users:

```php
        $conv = TBMsg::getConversationByTwoUsers($userA_id, $userB_id);
```
Simply gives you an id of the conversation between two users, this was created for redirecting to the conversation page when user tries to send a message to another user, so if there is no id returned that means that those users has no conversation yet, so we could create one.




#### Add a new message to conversation:

```php
        $conv = TBMsg::addMessageToConversation($conv_id, $user_id, $content);
```
Simply add a message to an exiting conversation, content is the message text.



#### Create a new conversation:

```php
        $conv = TBMsg::createConversation($users_ids=array());
```
Creates a new conversation with the users id's you passed in the array.





#### Get all users in conversation:

```php
        $conv = TBMsg::getUsersInConversation($conv_id);
```
returns an array of user id in the conversation.




#### Get all users in conversation:

```php
        $conv = TBMsg::deleteConversation($conv_id, $user_id);
```
"Deletes" the conversation from a specifc user view.



#### Check if user is in conversation:

```php
        $conv = TBMsg::isUserInConversation($conv_id, $user_id);
```
True or False if user is in conversation.




#### Get number of unread messages for specific user:

```php
        $conv = TBMsg::getNumOfUnreadMsgs($user_id);
```
return an integer of number of unread messages for specific user.





#### Mark all messages as "read" for specifc user in conversation:

```php
        $conv = TBMsg::markReadAllMessagesInConversation($conv_id, $user_id);
```
