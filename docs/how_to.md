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




#### Delete conversation:

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

## Example
```php
        public function conversations($convId=null) {
            $currentUser = Auth::user();
            //get the conversations
            $convs = TBMsg::getUserConversations( $currentUser->id );
            //array for storing our users data, as that Tbmsg only provides user id's
            $participants = [];
    
            //gathering participants
            foreach ( $convs as $conv ) {
                $participants = array_merge($participants, $conv->getAllParticipants());
            }
            //making sure each user appears once
            $participants = array_unique($participants);
    
            //getting all data of participants
            $viewUsers = [];
            if ( !empty($participants) ) {
                $users = User::whereIn('id', $participants)->with('profileImage')->getDictionary();
                
            }
            
            return View::make('conversations_page')
                ->with('users', $users)
                ->with('user', $currentUser)
                ->with('convs', $convs);
        }
```
