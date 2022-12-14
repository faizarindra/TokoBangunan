describe("Authorization", () => {
    before(() => {
        // runs once before all tests in the block
    });

    beforeEach(() => {
        cy.visit("/home");
        // runs before each test in the block
    });

    afterEach(() => {
        // runs after each test in the block
        // cy.visit("/_testing/delete-booking");
    });

    after(() => {
        // runs once after all tests in the block
    });

    it("akan membuka halaman register dan mencoba register", () => {
        cy.visit("/register");

        cy.contains("Register Account");

        cy.get('input[name="name"]').type("Test User", { force: true });
        cy.get('input[name="email"]').type("testuser@gmail.com", {
            force: true,
        });
        cy.get('input[name="password"]').type("12345678", { force: true });
        cy.get('input[name="password_confirmation"]').type("12345678", {
            force: true,
        });
        cy.get('button[type="submit"]').contains("Register");
        cy.get('button[type="submit"]').eq(0).click({ force: true });

        cy.contains("PRODUK TERLARIS");

        cy.visit("/_testing/delete-users");
    });

    it("akan membuka halaman login dengan existing user", () => {
        cy.visit("/_testing/create-users");

        cy.visit("/login");

        cy.contains("Log In Your Account");

        cy.get('input[name="email"]').type("aini@gmail.com", { force: true });
        cy.get('input[name="password"]').type("akuimuet123", { force: true });
        cy.get('button[type="submit"]').contains("Login");
        cy.get('button[type="submit"]').eq(0).click({ force: true });

        cy.contains("PRODUK TERLARIS");

        cy.visit("/_testing/delete-users");
    });
});
