describe('empty spec', () => {
  it('passes', () => {
    cy.visit('http://127.0.0.1:8000/')
    cy.get('.header__content-right > .header__actions > .ps-block--user-header > .ps-block__right > [href="http://127.0.0.1:8000/login"]').click()
    cy.get('#email').type('faiz@gmail.com')
    cy.get('#password').type('password')
    cy.get('#sign-in > .ps-form__content > .submtit > .ps-btn').click()
    cy.get('.header__content-right > .header__actions > .ps-block--user-header > .ps-block__right').click()
    cy.get('.ps-widget__content > ul > :nth-child(8) > a').click()
    cy.get(':nth-child(1) > .form-group > .form-control').type('Bangunan Pride')
    cy.get(':nth-child(2) > .form-group > .form-control').type('Jl. Semanggi Timur')
    cy.get('.col-sm-12 > .form-group > .form-control').type('Toko ini menyediakan Berbagai macam material bangunan')
    cy.get(':nth-child(4) > .form-group > .form-control').select(11)
    cy.get(':nth-child(5) > .form-group > .form-control').select(18)
    cy.get(':nth-child(6) > .form-group > .form-control').select(3)
    cy.get('.form-control-file').selectFile('cypress/e2e/p.jpg')
    cy.get('.submit > .ps-btn').click()
  })
})