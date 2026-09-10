describe("Login on Captive Portal with Form", () => {
  const cypressEnv = Cypress.env("HL_ENV") || "local";
  const envConfig = Cypress.env(cypressEnv);
  const timeout = 5000;

  before(() => {
    cy.startFlow(true, true);
  });

  // Use form as login method
  it("Uses form as login method", () => {
    cy.log('log', envConfig);



    cy.get('#logByEmail', { timeout }).should("be.visible");
    cy.wait(1000);
  });
  
  it("User fill basic data", () => {
    cy.get("input[name='refShareStep2firstName']", { timeout }).type("John");
    cy.get("input[name='refShareStep2lastName']", { timeout }).type("Doe");
    cy.get("input[name='refShareStep2email']", { timeout }).type("johndoe@example.org");
    cy.get("select[name='refShareStep2gender']", { timeout }).select("male");
    cy.get("select[name='refShareStep2year']", { timeout }).select((String) (new Date().getFullYear() - 5));
    cy.get("select[name='refShareStep2month']", { timeout }).select("10");
    cy.get("select[name='refShareStep2day']", { timeout }).select("26");
    
    cy.get(".send-email-form-button", { timeout }).should('have.class', 'disabled')
    
    cy.get("[name='gdpr_year']").then($check => {
      $check.click();
    });

  });

  // User arrives stay wifi redirect page
  it("Stay-wifi-redirect page reached", () => {
    cy.get(".send-email-form-button", { timeout }).click();
    cy.url().should("include", "stay-wifi-redirect");
  });

  // Introduce room number if active and finish login process
  it("User finish captive portal process", () => {
    cy.finishFlow(envConfig.validRoom);
  });
});