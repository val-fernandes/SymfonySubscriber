# SymfonySubscriber

## Installation
Follow these steps to set up the project:

### 1: Clone the Repository
```bash
git clone https://github.com/val-fernandes/SymfonySubscriber.git
cd laravel-12-2fa
```

### 2: Install Dependencies
```bash
composer install
```

### 3: Set Up Environment File
Edit `.env` and configure your **API settings**:
```ini
CRM_API_URL="https://api-url.com/"
CRM_API_TOKEN="api-access-token"
```

### 4: Start Development Server
```bash
symfony server:start
```

Access the app at `http://127.0.0.1:8000`

## Documentation
This document outlines the development of a **CrmApiClient** class designed to manage all external communication with our CRM API. The primary focus of this client is to handle the creation of subscribers and their subsequent enquiries. This implementation will leverage Symfony's HttpClient component for robust and efficient API interactions.

### Workflow Overview
The overall workflow is structured around three core components:
1. **CrmApiClient:** This class will serve as the central hub for all interactions with the CRM API. It will encapsulate the logic for making API calls, handling authentication, and processing responses. Key features will include robust error handling and seamless JSON encoding and decoding.
2. **ApiController:** A Symfony controller will expose the necessary API endpoints. Its primary responsibilities will be to receive and validate incoming requests for subscriber and enquiry creation, utilize the **CrmApiClient** to communicate with the external CRM, and return standardized JSON responses.
3. **Frontend Forms & Templates:** Self-contained HTML and JavaScript templates will provide the user interface for data submission. These forms will handle user input and initiate API calls to the Symfony backend.

### Implementation Details

The **CrmApiClient** will be the cornerstone of this integration. Its responsibilities will include:
- Methods for creating new subscribers and submitting enquiries.
- Functionality to retrieve subscriber lists.
- Comprehensive error handling to manage API connection issues or invalid responses.
- Efficient serialization and deserialization of JSON data.

The ApiController will be updated to:
- Implement robust validation for all incoming data.
- Facilitate communication between the frontend and the CrmApiClient.
- Return clear and consistent JSON responses, including success messages and error details.

### Frontend
The frontend will be responsible for the user-facing interaction:

- **Subscriber Form:** This form will capture the user's email, name, date of birth, and marketing consent preferences, including selections for specific mailing lists. The Fetch API will be used to submit this data to our Symfony backend.
- **Enquiry Form:** Upon a successful subscriber submission, a unique subscriber ID will be generated. This ID will be used to create a seamless transition for the user to submit an enquiry. The enquiry form will feature a message field, and the Fetch API will handle the submission.

### Error Management
A comprehensive error management strategy will be implemented across all three components—the **CrmApiClient**, the **ApiController**, and the frontend—to ensure that any issues are handled gracefully, providing clear and informative feedback to the user or logging detailed error information for developers.
