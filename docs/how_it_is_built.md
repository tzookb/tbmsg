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