## Summary

(Summarize the new feature concisely)

## Task Ticket Link

(Provide the ticket URL from Backlog)

## Relevant Documents/References

(Paste relevant documents/references for this feature. e.g Reference for UI [Mockup/Adobe XD/Figma Design], Screenshots, Hyperlinks, Snippets, etc.)

## Prerequisite Steps before Testing

(What are the first few steps the reviewer should do?)

Example:    
1. Run `npm install`
2. Run `php artisan migrate`

## Test Plan / Steps to Execute

(Write a step by step description on how the reviewer should test it - **this is very important**)

Example:    
1. Open Postman (or any other API platform you have available)
2. Create a new `POST` request for `/login`
3. Supply necessary credentials
3. Click 'Send' button to test login API feature

## Acceptance Criteria

(What should be the results that this feature is expected to fulfill in order for the user/reviewer to accept the implementation? Does the feature do what it is supposed to do?)

Example:    
1. Login API should return the Access and Refresh Tokens
2. User is now successfully logged in

## Code Coverage

(Upload at least 2 screenshots of the code coverage report's summary produced by executing `./phpunit tests --coverage-text`. It should be from the LATEST `gm` branch AND this MR's branch.)

## Test Evidence

(OPTIONAL. Add the link to the screen recording of your local testing for the changes made. You can use this tool to screen record `https://www.loom.com`)