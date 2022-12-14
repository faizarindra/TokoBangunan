describe('empty spec', () => {
  it('passes', () => {
    cy.visit('http://127.0.0.1:8000/login')
    cy.get('#email').type('hilmy@gmail.com')
    cy.get('#password').type('hilmy123')
    cy.get('#sign-in > .ps-form__content > .submtit > .ps-btn').click()
  })
})
