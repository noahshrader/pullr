describe('Streamboard', function () {
    it('Should be open', function () {
        expect(browser.getTitle()).toEqual('Streamboard');
    });
    it('Should contain donations', function(){
        var donations = element.all(by.repeater('donation in donationsService.donations'));
        expect(donations.count()).toBeGreaterThan(1);
        expect(donations.count()).toBeLessThan(21);
    });
})