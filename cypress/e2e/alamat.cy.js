describe('empty spec', () => {
  it('passes', () => {
    cy.visit('http://127.0.0.1:8000/login')
    cy.get('#email').type('hilmy@gmail.com')
    cy.get('#password').type('hilmy123')
    cy.get('#sign-in > .ps-form__content > .submtit > .ps-btn').click()
    cy.get('.header__content-right > .header__actions > .ps-block--user-header > .ps-block__right > a').click()
    cy.get('.ps-widget__content > ul > :nth-child(6) > a').click()
    cy.get('.ml-4').click()
    cy.get(':nth-child(1) > .form-group > .form-control').type('elok')
    cy.get(':nth-child(2) > .form-group > .form-control').type('089123456789')
    cy.get('#alamat').type('Jalan Senggani No.35')
    cy.get(':nth-child(4) > .form-group > .form-control').select(11)
    cy.get(':nth-child(5) > .form-group > .form-control').select(18)
    cy.get(':nth-child(6) > .form-group > .form-control').select(4)
    cy.get(':nth-child(7) > .form-group > .form-control').type('62352')
    cy.get('.submit > .ps-btn').click()
  })
})
