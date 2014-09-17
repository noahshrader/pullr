describe('Streamboard', function () {
    it('Should be open', function () {
        expect(browser.getTitle()).toEqual('Streamboard');
    });
    it('Should contain donations', function () {
        var donations = element(by.id('donations')).all(by.repeater('donation in donationsService.donations'));

        expect(donations.count()).toBeGreaterThan(1);
        expect(donations.count()).toBeLessThan(21);
    });
    it('Stats should contain 3 top donors', function () {
        var topDonors = element.all(by.repeater('donor in donationsService.stats.top_donors'));
        expect(topDonors.count()).toBe(3);
    })
})