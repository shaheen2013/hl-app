Cypress.Commands.add("startFlow", (accommodated, notifications) => {
  const cypressEnv = Cypress.env("HL_ENV") || "local";
  const envConfig = Cypress.env(cypressEnv);
  let gdprDiv = ".hotelClient"

  // Visit captive portal
  cy.visit(envConfig.portalUrl);

  // Accept GDPR
  if (accommodated) {
    cy.get("#hotelClientBtn").click();
  } else {
    cy.get("#notHotelClientBtn").click();
    gdprDiv = ".notHotelClient"
  }

  // Accept second page of GDPR
  if (notifications) {
    cy.get("body").then((body, gdprDiv) => {
      if (body.find(`${gdprDiv} .acceptGdprNotifications`).length > 0) {
        cy.get(`${gdprDiv} .acceptGdprNotifications`).then($check => {
          $check.click();
        });
      }
    });
    
  }
    
  cy.get(`${gdprDiv} .acceptGdprConditions`)
    .first()
    .click();

  // Remove Wifi Offer model if set
  cy.get("body").then((body) => {
    if (body.find(".dismiss-wifi-offer").length > 0) {
        cy.get(".dismiss-wifi-offer").click();
    }
  });
});

Cypress.Commands.add("finishFlow", (room) => {
  cy.get("body").then((body) => {
    if (body.find("#hotel-room-number").length > 0) {
      cy.get("#hotel-room-number").type("InvalidRoomNumber");
      cy.get("#roomSubmitBtn").should("not.be.visible");
      cy.get("#hotel-room-number").clear();
      cy.get("#hotel-room-number").type(room);
      cy.get("#roomSubmitBtn").click();
    } else {
      cy.get("#wifiSubmitBtn").click();
    }
  });


});