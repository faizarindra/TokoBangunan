describe('empty spec', () => {
  it('passes', () => {
    cy.visit('http://127.0.0.1:8000')
    cy.get('#query-search').type('semen')
    cy.get('#btn-search').click()
  })
})
