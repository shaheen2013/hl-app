describe("Login on Captive Portal with Facebook", () => {
  const timeout = 5000;

  before(() => {
    cy.startFlow(false, true);
  });

  //Reach login screen
  it("Click on facebook button", () => {
    // Click on facebook button
    cy.get(".btn-facebook", { timeout })
      .first()
      .click();
      
    cy.url({ timeout }).should("include", "www.facebook.com");
  });
}); 