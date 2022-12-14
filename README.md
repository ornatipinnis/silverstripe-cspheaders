# silverstripe-cspheaders
Add a CSP header to your Silverstripe site: 'content-security-policy'

## How to configure
Create a yml file and add an array to the object for the CSP setup.

```
ornatipinnis\Extensions\CSPHeaders:
  CSPArray:
    - default-src: 
      - "'self'"
      - "data:"
    - script-src: 
      - "'self'"
      - "data:"
      - "*.google.com"
```
The top level of the array is the CSP block, eg 'default-src', add each item below that remembering to quote appropriate values.
