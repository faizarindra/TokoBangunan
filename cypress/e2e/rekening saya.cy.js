describe('empty spec', () => {
  it('passes', () => {
    cy.visit('http://127.0.0.1:8000/login')
    cy.get('#email').type('hilmy@gmail.com')
    cy.get('#password').type('hilmy123')
    cy.get('#sign-in > .ps-form__content > .submtit > .ps-btn').click()
    cy.get('.header__content-right > .header__actions > .ps-block--user-header > .ps-block__right > a').click()
    cy.get('.ps-widget__content > ul > :nth-child(8) > a').click()
    cy.get('.ps-widget__content > ul > :nth-child(5) > a').click()
    cy.get('.ml-4').click({force: true})
    cy.get('.col-sm-6 > .form-group > .form-control').select(2)
    cy.get(':nth-child(2) > .form-group > .form-control').type('6181607240')
    cy.get(':nth-child(3) > .form-group > .form-control').type('Muhammad Hilmy Iqbal')
    cy.get(':nth-child(4) > .form-group > .form-control').type('Malang')
    cy.get('.submit > .ps-btn').click()
  })
})