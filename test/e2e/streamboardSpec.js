describe('Streamboard', function () {
    browser.driver.get(browser.baseUrl);
    var loginInput = browser.driver.findElement(by.id('loginform-login'));
    loginInput.sendKeys('Stanislav@gmail.com');
    var loginPassword = browser.driver.findElement(by.id('loginform-password'));
    loginPassword.sendKeys('Stanislav');
    var submitButton = browser.driver.findElement(by.css('.btn-primary'));
    submitButton.click();

    beforeEach(function(){
        browser.driver.get(browser.baseUrl + 'app/streamboard');
    });

    describe('followers alerts', function () {
        it('login page', function(){
            browser.driver.sleep(4000);
            browser.driver.getCurrentUrl().then(function(url){
                console.log(url);
            });
        });
    });
})