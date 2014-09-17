exports.config = {
    allScriptsTimeout: 30000,

    specs: [
        'e2e/*.js'
    ],

    capabilities: {
        'browserName': 'chrome'
    },

    rootElement: '.streamboardContainer',
    chromeOnly: true,

    baseUrl: 'http://localhost:8000/pullr/frontend/web/',

    framework: 'jasmine',

    jasmineNodeOpts: {
        defaultTimeoutInterval: 30000
    },
    onPrepare: function () {
        browser.driver.get(browser.baseUrl);
        browser.driver.findElement(by.id('loginform-login')).sendKeys('Stanislav@gmail.com');
        browser.driver.findElement(by.id('loginform-password')).sendKeys('Stanislav');
        browser.driver.findElement(by.css('.btn-primary')).click();

        browser.driver.get(browser.baseUrl + 'app/streamboard');
        // Login takes some time, so wait until it's done.
        // For the test app's login, we know it's done when it redirects to
        // index.html.
        browser.driver.wait(function () {
            return browser.driver.getCurrentUrl().then(function (url) {
                return /app\/streamboard/.test(url);
            });
        });
    }
};
