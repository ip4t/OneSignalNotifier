# OneSignalNotifier

A simple Laravel package to send OneSignal notifications.

## Installation

```bash
composer require nourallah/onesignalnotifier

## Configuration
Add your OneSignal credentials to your .env file:
 
ONESIGNAL_APP_ID=your-app-id
ONESIGNAL_REST_API_KEY=your-rest-api-key


## Usage
use Nourallah\OneSignalNotifier\OneSignalNotifier;

$notifier = new OneSignalNotifier();

$notifier->sendToAll('Hello everyone!');

Send notification with Arabic and English
$notifier->sendToAll([
    'en' => 'Hello everyone',
    'ar' => 'مرحبا بالجميع',
]);


# Send notification to a specific user
$notifier->sendToUser('player-id-123', 'You have a new message');
