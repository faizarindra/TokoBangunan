describe('DashboardPenjual', () => {
  it('User berhasil masuk ke Toko', () => {
    cy.visit('http://127.0.0.1:8000/')
    cy.get('.header__content-right > .header__actions > .ps-block--user-header > .ps-block__right > [href="http://127.0.0.1:8000/login"]').click()
    cy.get('#email').type('faiz@gmail.com')
    cy.get('#password').type('password')
    cy.get('#sign-in > .ps-form__content > .submtit > .ps-btn').click()
    cy.get('.header__content-right > .header__actions > .ps-block--user-header > .ps-block__right').click()
    cy.get('.ps-widget__content > ul > :nth-child(8) > a').click()

  })
})