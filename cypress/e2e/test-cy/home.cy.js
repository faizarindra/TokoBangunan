describe("Home and Products", () => {
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

    it("akan membuka list produk di kategori", () => {
        cy.contains("PRODUK TERLARIS");

        cy.get(".more").contains("More").click();
        cy.contains("Produk di dalam kategori Semen");
    });

    it("akan membuka list produk di merk", () => {
        cy.contains("PRODUK TERLARIS");

        cy.get(".ps-block__content").contains("Tiga Roda").click();
        cy.contains("Produk di dalam merk Tiga Roda");
    });

    it("akan membuka detail produk", () => {
        cy.contains("PRODUK TERLARIS");

        cy.get(".ps-block__content").contains("Tiga Roda").click();
        cy.contains("Produk di dalam merk Tiga Roda");
        cy.get(".ps-product__title").contains("Semen Karungan").eq(0).click();
    });

    it("akan menambahkan ke keranjang", () => {
        // cy.visit("/_testing/create-users");

        cy.visit("/login");

        cy.contains("Log In Your Account");

        cy.get('input[name="email"]').type("aini@gmail.com", { force: true });
        cy.get('input[name="password"]').type("akuimuet123", { force: true });
        cy.get('button[type="submit"]').contains("Login");
        cy.get('button[type="submit"]').eq(0).click({ force: true });

        cy.contains("PRODUK TERLARIS");

        cy.get(".ps-block__content").contains("Tiga Roda").click();
        cy.contains("Produk di dalam merk Tiga Roda");
        cy.get(".ps-product__title").contains("Semen Karungan").eq(0).click();

        cy.get("#addtocart").contains("Tambahkan ke keranjang").eq(0).click();

        cy.get(".ps-cart__footer")
            .contains("Lihat Keranjang")
            .eq(0)
            .click({ force: true });

        // cy.visit("/_testing/delete-keranjang");
    });

    it("akan menambahkan ke favorit", () => {
        cy.visit("/_testing/delete-keranjang");

        cy.visit("/login");

        cy.contains("Log In Your Account");

        cy.get('input[name="email"]').type("aini@gmail.com", { force: true });
        cy.get('input[name="password"]').type("akuimuet123", { force: true });
        cy.get('button[type="submit"]').contains("Login");
        cy.get('button[type="submit"]').eq(0).click({ force: true });

        cy.contains("PRODUK TERLARIS");

        cy.get(".ps-block__content").contains("Tiga Roda").click();
        cy.contains("Produk di dalam merk Tiga Roda");
        cy.get(".ps-product__title").contains("Semen Karungan").eq(0).click({
            force: true,
        });

        cy.get(".icon-heart").eq(1).click({ force: true });
        cy.get(".icon-heart").eq(0).click({ force: true });
    });

    it("akan menghapus favorit", () => {
        cy.visit("/_testing/delete-keranjang");

        cy.visit("/login");

        cy.contains("Log In Your Account");

        cy.get('input[name="email"]').type("aini@gmail.com", { force: true });
        cy.get('input[name="password"]').type("akuimuet123", { force: true });
        cy.get('button[type="submit"]').contains("Login");
        cy.get('button[type="submit"]').eq(0).click({ force: true });

        cy.contains("PRODUK TERLARIS");

        cy.get(".ps-block__content").contains("Tiga Roda").click();
        cy.contains("Produk di dalam merk Tiga Roda");
        cy.get(".ps-product__title").contains("Semen Karungan").eq(0).click({
            force: true,
        });

        // cy.get(".icon-heart").eq(1).click({ force: true });
        cy.get("#favorite-form").eq(0).submit({ force: true });
    });
});
