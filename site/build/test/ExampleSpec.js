// jasmine example using require
// Docs: http://jasmine.github.io/2.0/introduction.html
define(["underscore", "jquery"], function(_, $){
    console.log("underscore: ", _);
    console.log("jquery: ", $);

    describe("The 'toBe' matcher compares with ===", function() {

        it("and has a positive case", function() {
            expect(true).toBe(true);
        });

        it("and can have a negative case", function() {
            expect(false).not.toBe(true);
        });
    });
});