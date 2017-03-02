<?php
					
	if($sendcopytome == true){  
										
		if($finalsendtome === $lang['form-send-to-me-option-1']){
			
			if($automessage == true){
	
				if ($mysql == true){
					
					if($signature == true){
										
						$paypalcustomer = Braintree_Customer::create(array(
							'id' => $finalcustomerid,
							'firstName' => $finalfirstname,
							'lastName' => $finallastname,
							'email' => $finalemail,
							'paymentMethodNonce' => $finalnonce
						));
					
						if($paypalcustomer->success){
							
							$finalpaypaltoken = $paypalcustomer->customer->paymentMethods[0]->token;
																											
							$paypaltransaction = Braintree_Transaction::sale(array(
								'amount' => $finalpaymentprice,
								'paymentMethodToken' => $finalpaypaltoken,										
								'options' => array(
									'submitForSettlement' => $submitforsettlement,
									'storeInVault' => $storeinvault
								)
							));
						
							if($paypaltransaction->success){
								
								include 'functions/signature/signature.php';
								
								if($adminemail->send()){
								
									include 'messages/customer/automessage-payment.php';
									include 'automessage.php';
								
									if($automail->send()){
										
										include 'messages/customer/message-copy-payment.php';
										include 'emailcopy.php';
										
										if($emailcopy->send()){
											if($sendmobile == true){
												if($redirect == true){
													if($multilanguage == true) {
														$mobile->send($notification, $phone, $unicode);
														echo $lang['form-wait-message'];
														db_insert_payment($tablenamepayment,$insertpaymentvalues);	
														redirecttime($paymentsuccess2);
													} else {
														$mobile->send($notification, $phone, $unicode);
														echo $lang['form-wait-message'];
														db_insert_payment($tablenamepayment,$insertpaymentvalues);	
														redirecttime($paymentsuccess1);
													}
												} else {
													$mobile->send($notification, $phone, $unicode);
													db_insert_payment($tablenamepayment,$insertpaymentvalues);
													echo $lang['form-success-payment-message'];
												}
											} else {
												if($redirect == true){
													if($multilanguage == true) {
														echo $lang['form-wait-message'];
														db_insert_payment($tablenamepayment,$insertpaymentvalues);	
														redirecttime($paymentsuccess2);
													} else {
														echo $lang['form-wait-message'];
														db_insert_payment($tablenamepayment,$insertpaymentvalues);	
														redirecttime($paymentsuccess1);
													}
												} else {														
													db_insert_payment($tablenamepayment,$insertpaymentvalues);	
													echo $lang['form-success-payment-message'];
												}
											}
										} else {
											if($redirect == true){
												if($multilanguage == true) {
													echo $lang['form-wait-message'];
													redirecttime($emailcopyerror2);																
												} else {
													echo $lang['form-wait-message'];
													redirecttime($emailcopyerror1);
												}
											} else {
												echo $lang['form-email-copy-message'];
											}
										}
									} else {
										if($redirect == true){
											if($multilanguage == true) {
												echo $lang['form-wait-message'];
												redirecttime($automailerror2);																
											} else {
												echo $lang['form-wait-message'];
												redirecttime($automailerror1);
											}
										} else {
											echo $lang['form-automessage-message'];
										}
									}	
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											redirecttime($mailerror2);																
										} else {
											echo $lang['form-wait-message'];
											redirecttime($mailerror1);
										}
									} else {
										echo $lang['form-mail-message'];
									}
								}
							} else {
								if($redirect == true){
									if($multilanguage == true) {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror2);																
									} else {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror1);
									}
								} else {
									echo $lang['form-transaction-message'];
								}										
							}
						} else {
							if($redirect == true){
								if($multilanguage == true) {
									echo $lang['form-wait-message'];
									redirecttime($customererror2);																
								} else {
									echo $lang['form-wait-message'];
									redirecttime($customererror1);
								}
							} else {
								echo $lang['form-customer-message'];
							}										
						}
						
					} else {
						
						$paypalcustomer = Braintree_Customer::create(array(
							'id' => $finalcustomerid,
							'firstName' => $finalfirstname,
							'lastName' => $finallastname,
							'email' => $finalemail,
							'paymentMethodNonce' => $finalnonce
						));
					
						if($paypalcustomer->success){
							
							$finalpaypaltoken = $paypalcustomer->customer->paymentMethods[0]->token;
														
							$paypaltransaction = Braintree_Transaction::sale(array(
								'amount' => $finalpaymentprice,
								'paymentMethodToken' => $finalpaypaltoken,
								'options' => array(
									'submitForSettlement' => $submitforsettlement,
									'storeInVault' => $storeinvault
								)
							));
						
							if($paypaltransaction->success){
																																									
								if($adminemail->send()){
								
									include 'messages/customer/automessage-payment.php';
									include 'automessage.php';
								
									if($automail->send()){
										
										include 'messages/customer/message-copy-payment.php';
										include 'emailcopy.php';
										
										if($emailcopy->send()){
											if($sendmobile == true){
												if($redirect == true){
													if($multilanguage == true) {
														$mobile->send($notification, $phone, $unicode);
														echo $lang['form-wait-message'];
														db_insert_payment($tablenamepayment,$insertpaymentvalues);	
														redirecttime($paymentsuccess2);
													} else {
														$mobile->send($notification, $phone, $unicode);
														echo $lang['form-wait-message'];
														db_insert_payment($tablenamepayment,$insertpaymentvalues);	
														redirecttime($paymentsuccess1);
													}
												} else {
													$mobile->send($notification, $phone, $unicode);
													db_insert_payment($tablenamepayment,$insertpaymentvalues);
													echo $lang['form-success-payment-message'];
												}
											} else {
												if($redirect == true){
													if($multilanguage == true) {
														echo $lang['form-wait-message'];
														db_insert_payment($tablenamepayment,$insertpaymentvalues);	
														redirecttime($paymentsuccess2);
													} else {
														echo $lang['form-wait-message'];
														db_insert_payment($tablenamepayment,$insertpaymentvalues);	
														redirecttime($paymentsuccess1);
													}
												} else {														
													db_insert_payment($tablenamepayment,$insertpaymentvalues);	
													echo $lang['form-success-payment-message'];
												}
											}
										} else {
											if($redirect == true){
												if($multilanguage == true) {
													echo $lang['form-wait-message'];
													redirecttime($emailcopyerror2);																
												} else {
													echo $lang['form-wait-message'];
													redirecttime($emailcopyerror1);
												}
											} else {
												echo $lang['form-email-copy-message'];
											}
										}
									} else {
										if($redirect == true){
											if($multilanguage == true) {
												echo $lang['form-wait-message'];
												redirecttime($automailerror2);																
											} else {
												echo $lang['form-wait-message'];
												redirecttime($automailerror1);
											}
										} else {
											echo $lang['form-automessage-message'];
										}
									}	
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											redirecttime($mailerror2);																
										} else {
											echo $lang['form-wait-message'];
											redirecttime($mailerror1);
										}
									} else {
										echo $lang['form-mail-message'];
									}
								}
							} else {
								if($redirect == true){
									if($multilanguage == true) {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror2);																
									} else {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror1);
									}
								} else {
									echo $lang['form-transaction-message'];
								}										
							}
						} else {
							if($redirect == true){
								if($multilanguage == true) {
									echo $lang['form-wait-message'];
									redirecttime($customererror2);																
								} else {
									echo $lang['form-wait-message'];
									redirecttime($customererror1);
								}
							} else {
								echo $lang['form-customer-message'];
							}										
						}
					}
					
				} else {
					
					if($signature == true){
										
						$paypalcustomer = Braintree_Customer::create(array(
							'id' => $finalcustomerid,
							'firstName' => $finalfirstname,
							'lastName' => $finallastname,
							'email' => $finalemail,
							'paymentMethodNonce' => $finalnonce
						));
					
						if($paypalcustomer->success){
							
							$finalpaypaltoken = $paypalcustomer->customer->paymentMethods[0]->token;
																	
							$paypaltransaction = Braintree_Transaction::sale(array(
								'amount' => $finalpaymentprice,
								'paymentMethodToken' => $finalpaypaltoken, 
								'options' => array(
									'submitForSettlement' => $submitforsettlement,
									'storeInVault' => $storeinvault
								)
							));
						
							if($paypaltransaction->success){
								
								include 'functions/signature/signature.php';
								
								if($adminemail->send()){
								
									include 'messages/customer/automessage-payment.php';
									include 'automessage.php';
								
									if($automail->send()){
										
										include 'messages/customer/message-copy-payment.php';
										include 'emailcopy.php';
										
										if($emailcopy->send()){
											if($sendmobile == true){
												if($redirect == true){
													if($multilanguage == true) {
														$mobile->send($notification, $phone, $unicode);
														echo $lang['form-wait-message'];
														redirecttime($paymentsuccess2);
													} else {
														$mobile->send($notification, $phone, $unicode);
														echo $lang['form-wait-message'];
														redirecttime($paymentsuccess1);
													}
												} else {
													$mobile->send($notification, $phone, $unicode);
													echo $lang['form-success-payment-message'];
												}
											} else {
												if($redirect == true){
													if($multilanguage == true) {
														echo $lang['form-wait-message'];
														redirecttime($paymentsuccess2);
													} else {
														echo $lang['form-wait-message'];
														redirecttime($paymentsuccess1);
													}
												} else {														
													echo $lang['form-success-payment-message'];
												}
											}
										} else {
											if($redirect == true){
												if($multilanguage == true) {
													echo $lang['form-wait-message'];
													redirecttime($emailcopyerror2);																
												} else {
													echo $lang['form-wait-message'];
													redirecttime($emailcopyerror1);
												}
											} else {
												echo $lang['form-email-copy-message'];
											}
										}
									} else {
										if($redirect == true){
											if($multilanguage == true) {
												echo $lang['form-wait-message'];
												redirecttime($automailerror2);																
											} else {
												echo $lang['form-wait-message'];
												redirecttime($automailerror1);
											}
										} else {
											echo $lang['form-automessage-message'];
										}
									}	
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											redirecttime($mailerror2);																
										} else {
											echo $lang['form-wait-message'];
											redirecttime($mailerror1);
										}
									} else {
										echo $lang['form-mail-message'];
									}
								}
							} else {
								if($redirect == true){
									if($multilanguage == true) {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror2);																
									} else {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror1);
									}
								} else {
									echo $lang['form-transaction-message'];
								}										
							}
						} else {
							if($redirect == true){
								if($multilanguage == true) {
									echo $lang['form-wait-message'];
									redirecttime($customererror2);																
								} else {
									echo $lang['form-wait-message'];
									redirecttime($customererror1);
								}
							} else {
								echo $lang['form-customer-message'];
							}										
						}
						
					} else {
						
						$paypalcustomer = Braintree_Customer::create(array(
							'id' => $finalcustomerid,
							'firstName' => $finalfirstname,
							'lastName' => $finallastname,
							'email' => $finalemail,
							'paymentMethodNonce' => $finalnonce
						));
					
						if($paypalcustomer->success){
							
							$finalpaypaltoken = $paypalcustomer->customer->paymentMethods[0]->token;
																	
							$paypaltransaction = Braintree_Transaction::sale(array(
								'amount' => $finalpaymentprice,
								'paymentMethodToken' => $finalpaypaltoken, 
								'options' => array(
									'submitForSettlement' => $submitforsettlement,
									'storeInVault' => $storeinvault
								)
							));
						
							if($paypaltransaction->success){
																																									
								if($adminemail->send()){
								
									include 'messages/customer/automessage-payment.php';
									include 'automessage.php';
								
									if($automail->send()){
										
										include 'messages/customer/message-copy-payment.php';
										include 'emailcopy.php';
										
										if($emailcopy->send()){
											if($sendmobile == true){
												if($redirect == true){
													if($multilanguage == true) {
														$mobile->send($notification, $phone, $unicode);
														echo $lang['form-wait-message'];
														redirecttime($paymentsuccess2);
													} else {
														$mobile->send($notification, $phone, $unicode);
														echo $lang['form-wait-message'];
														redirecttime($paymentsuccess1);
													}
												} else {
													$mobile->send($notification, $phone, $unicode);
													echo $lang['form-success-payment-message'];
												}
											} else {
												if($redirect == true){
													if($multilanguage == true) {
														echo $lang['form-wait-message'];
														redirecttime($paymentsuccess2);
													} else {
														echo $lang['form-wait-message'];
														redirecttime($paymentsuccess1);
													}
												} else {														
													echo $lang['form-success-payment-message'];
												}
											}
										} else {
											if($redirect == true){
												if($multilanguage == true) {
													echo $lang['form-wait-message'];
													redirecttime($emailcopyerror2);																
												} else {
													echo $lang['form-wait-message'];
													redirecttime($emailcopyerror1);
												}
											} else {
												echo $lang['form-email-copy-message'];
											}
										}
									} else {
										if($redirect == true){
											if($multilanguage == true) {
												echo $lang['form-wait-message'];
												redirecttime($automailerror2);																
											} else {
												echo $lang['form-wait-message'];
												redirecttime($automailerror1);
											}
										} else {
											echo $lang['form-automessage-message'];
										}
									}	
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											redirecttime($mailerror2);																
										} else {
											echo $lang['form-wait-message'];
											redirecttime($mailerror1);
										}
									} else {
										echo $lang['form-mail-message'];
									}
								}
							} else {
								if($redirect == true){
									if($multilanguage == true) {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror2);																
									} else {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror1);
									}
								} else {
									echo $lang['form-transaction-message'];
								}										
							}
						} else {
							if($redirect == true){
								if($multilanguage == true) {
									echo $lang['form-wait-message'];
									redirecttime($customererror2);																
								} else {
									echo $lang['form-wait-message'];
									redirecttime($customererror1);
								}
							} else {
								echo $lang['form-customer-message'];
							}										
						}
					}
				}
				
			} else {
				
				if ($mysql == true){
					
					if($signature == true){
										
						$paypalcustomer = Braintree_Customer::create(array(
							'id' => $finalcustomerid,
							'firstName' => $finalfirstname,
							'lastName' => $finallastname,
							'email' => $finalemail,
							'paymentMethodNonce' => $finalnonce
						));
					
						if($paypalcustomer->success){
							
							$finalpaypaltoken = $paypalcustomer->customer->paymentMethods[0]->token;
																	
							$paypaltransaction = Braintree_Transaction::sale(array(
								'amount' => $finalpaymentprice,
								'paymentMethodToken' => $finalpaypaltoken, 
								'options' => array(
									'submitForSettlement' => $submitforsettlement,
									'storeInVault' => $storeinvault
								)
							));
						
							if($paypaltransaction->success){
								
								include 'functions/signature/signature.php';
								
								if($adminemail->send()){
								
									include 'messages/customer/message-copy-payment.php';
									include 'emailcopy.php';
									
									if($emailcopy->send()){
										if($sendmobile == true){
											if($redirect == true){
												if($multilanguage == true) {
													$mobile->send($notification, $phone, $unicode);
													echo $lang['form-wait-message'];
													db_insert_payment($tablenamepayment,$insertpaymentvalues);	
													redirecttime($paymentsuccess2);
												} else {
													$mobile->send($notification, $phone, $unicode);
													echo $lang['form-wait-message'];
													db_insert_payment($tablenamepayment,$insertpaymentvalues);	
													redirecttime($paymentsuccess1);
												}
											} else {
												$mobile->send($notification, $phone, $unicode);
												db_insert_payment($tablenamepayment,$insertpaymentvalues);
												echo $lang['form-success-payment-message'];
											}
										} else {
											if($redirect == true){
												if($multilanguage == true) {
													echo $lang['form-wait-message'];
													db_insert_payment($tablenamepayment,$insertpaymentvalues);	
													redirecttime($paymentsuccess2);
												} else {
													echo $lang['form-wait-message'];
													db_insert_payment($tablenamepayment,$insertpaymentvalues);	
													redirecttime($paymentsuccess1);
												}
											} else {														
												db_insert_payment($tablenamepayment,$insertpaymentvalues);	
												echo $lang['form-success-payment-message'];
											}
										}
									} else {
										if($redirect == true){
											if($multilanguage == true) {
												echo $lang['form-wait-message'];
												redirecttime($emailcopyerror2);																
											} else {
												echo $lang['form-wait-message'];
												redirecttime($emailcopyerror1);
											}
										} else {
											echo $lang['form-email-copy-message'];
										}
									}
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											redirecttime($mailerror2);																
										} else {
											echo $lang['form-wait-message'];
											redirecttime($mailerror1);
										}
									} else {
										echo $lang['form-mail-message'];
									}
								}
							} else {
								if($redirect == true){
									if($multilanguage == true) {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror2);																
									} else {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror1);
									}
								} else {
									echo $lang['form-transaction-message'];
								}										
							}
						} else {
							if($redirect == true){
								if($multilanguage == true) {
									echo $lang['form-wait-message'];
									redirecttime($customererror2);																
								} else {
									echo $lang['form-wait-message'];
									redirecttime($customererror1);
								}
							} else {
								echo $lang['form-customer-message'];
							}										
						}
						
					} else {
						
						$paypalcustomer = Braintree_Customer::create(array(
							'id' => $finalcustomerid,
							'firstName' => $finalfirstname,
							'lastName' => $finallastname,
							'email' => $finalemail,
							'paymentMethodNonce' => $finalnonce
						));
					
						if($paypalcustomer->success){
							
							$finalpaypaltoken = $paypalcustomer->customer->paymentMethods[0]->token;
																	
							$paypaltransaction = Braintree_Transaction::sale(array(
								'amount' => $finalpaymentprice,
								'paymentMethodToken' => $finalpaypaltoken, 
								'options' => array(
									'submitForSettlement' => $submitforsettlement,
									'storeInVault' => $storeinvault
								)
							));
						
							if($paypaltransaction->success){
																																									
								if($adminemail->send()){
								
									include 'messages/customer/message-copy-payment.php';
									include 'emailcopy.php';
									
									if($emailcopy->send()){
										if($sendmobile == true){
											if($redirect == true){
												if($multilanguage == true) {
													$mobile->send($notification, $phone, $unicode);
													echo $lang['form-wait-message'];
													db_insert_payment($tablenamepayment,$insertpaymentvalues);	
													redirecttime($paymentsuccess2);
												} else {
													$mobile->send($notification, $phone, $unicode);
													echo $lang['form-wait-message'];
													db_insert_payment($tablenamepayment,$insertpaymentvalues);	
													redirecttime($paymentsuccess1);
												}
											} else {
												$mobile->send($notification, $phone, $unicode);
												db_insert_payment($tablenamepayment,$insertpaymentvalues);
												echo $lang['form-success-payment-message'];
											}
										} else {
											if($redirect == true){
												if($multilanguage == true) {
													echo $lang['form-wait-message'];
													db_insert_payment($tablenamepayment,$insertpaymentvalues);	
													redirecttime($paymentsuccess2);
												} else {
													echo $lang['form-wait-message'];
													db_insert_payment($tablenamepayment,$insertpaymentvalues);	
													redirecttime($paymentsuccess1);
												}
											} else {														
												db_insert_payment($tablenamepayment,$insertpaymentvalues);	
												echo $lang['form-success-payment-message'];
											}
										}
									} else {
										if($redirect == true){
											if($multilanguage == true) {
												echo $lang['form-wait-message'];
												redirecttime($emailcopyerror2);																
											} else {
												echo $lang['form-wait-message'];
												redirecttime($emailcopyerror1);
											}
										} else {
											echo $lang['form-email-copy-message'];
										}
									}	
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											redirecttime($mailerror2);																
										} else {
											echo $lang['form-wait-message'];
											redirecttime($mailerror1);
										}
									} else {
										echo $lang['form-mail-message'];
									}
								}
							} else {
								if($redirect == true){
									if($multilanguage == true) {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror2);																
									} else {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror1);
									}
								} else {
									echo $lang['form-transaction-message'];
								}										
							}
						} else {
							if($redirect == true){
								if($multilanguage == true) {
									echo $lang['form-wait-message'];
									redirecttime($customererror2);																
								} else {
									echo $lang['form-wait-message'];
									redirecttime($customererror1);
								}
							} else {
								echo $lang['form-customer-message'];
							}										
						}
					}
					
				} else {
					
					if($signature == true){
										
						$paypalcustomer = Braintree_Customer::create(array(
							'id' => $finalcustomerid,
							'firstName' => $finalfirstname,
							'lastName' => $finallastname,
							'email' => $finalemail,
							'paymentMethodNonce' => $finalnonce
						));
					
						if($paypalcustomer->success){
							
							$finalpaypaltoken = $paypalcustomer->customer->paymentMethods[0]->token;
																	
							$paypaltransaction = Braintree_Transaction::sale(array(
								'amount' => $finalpaymentprice,
								'paymentMethodToken' => $finalpaypaltoken, 
								'options' => array(
									'submitForSettlement' => $submitforsettlement,
									'storeInVault' => $storeinvault
								)
							));
						
							if($paypaltransaction->success){
								
								include 'functions/signature/signature.php';
								
								if($adminemail->send()){
								
									include 'messages/customer/message-copy-payment.php';
									include 'emailcopy.php';
									
									if($emailcopy->send()){
										if($sendmobile == true){
											if($redirect == true){
												if($multilanguage == true) {
													$mobile->send($notification, $phone, $unicode);
													echo $lang['form-wait-message'];
													redirecttime($paymentsuccess2);
												} else {
													$mobile->send($notification, $phone, $unicode);
													echo $lang['form-wait-message'];
													redirecttime($paymentsuccess1);
												}
											} else {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-success-payment-message'];
											}
										} else {
											if($redirect == true){
												if($multilanguage == true) {
													echo $lang['form-wait-message'];
													redirecttime($paymentsuccess2);
												} else {
													echo $lang['form-wait-message'];
													redirecttime($paymentsuccess1);
												}
											} else {														
												echo $lang['form-success-payment-message'];
											}
										}
									} else {
										if($redirect == true){
											if($multilanguage == true) {
												echo $lang['form-wait-message'];
												redirecttime($emailcopyerror2);																
											} else {
												echo $lang['form-wait-message'];
												redirecttime($emailcopyerror1);
											}
										} else {
											echo $lang['form-email-copy-message'];
										}
									}	
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											redirecttime($mailerror2);																
										} else {
											echo $lang['form-wait-message'];
											redirecttime($mailerror1);
										}
									} else {
										echo $lang['form-mail-message'];
									}
								}
							} else {
								if($redirect == true){
									if($multilanguage == true) {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror2);																
									} else {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror1);
									}
								} else {
									echo $lang['form-transaction-message'];
								}										
							}
						} else {
							if($redirect == true){
								if($multilanguage == true) {
									echo $lang['form-wait-message'];
									redirecttime($customererror2);																
								} else {
									echo $lang['form-wait-message'];
									redirecttime($customererror1);
								}
							} else {
								echo $lang['form-customer-message'];
							}										
						}
						
					} else {
						
						$paypalcustomer = Braintree_Customer::create(array(
							'id' => $finalcustomerid,
							'firstName' => $finalfirstname,
							'lastName' => $finallastname,
							'email' => $finalemail,
							'paymentMethodNonce' => $finalnonce
						));
					
						if($paypalcustomer->success){
							
							$finalpaypaltoken = $paypalcustomer->customer->paymentMethods[0]->token;
																	
							$paypaltransaction = Braintree_Transaction::sale(array(
								'amount' => $finalpaymentprice,
								'paymentMethodToken' => $finalpaypaltoken, 
								'options' => array(
									'submitForSettlement' => $submitforsettlement,
									'storeInVault' => $storeinvault
								)
							));
						
							if($paypaltransaction->success){
																																									
								if($adminemail->send()){
								
									include 'messages/customer/message-copy-payment.php';
									include 'emailcopy.php';
									
									if($emailcopy->send()){
										if($sendmobile == true){
											if($redirect == true){
												if($multilanguage == true) {
													$mobile->send($notification, $phone, $unicode);
													echo $lang['form-wait-message'];
													redirecttime($paymentsuccess2);
												} else {
													$mobile->send($notification, $phone, $unicode);
													echo $lang['form-wait-message'];
													redirecttime($paymentsuccess1);
												}
											} else {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-success-payment-message'];
											}
										} else {
											if($redirect == true){
												if($multilanguage == true) {
													echo $lang['form-wait-message'];
													redirecttime($paymentsuccess2);
												} else {
													echo $lang['form-wait-message'];
													redirecttime($paymentsuccess1);
												}
											} else {														
												echo $lang['form-success-payment-message'];
											}
										}
									} else {
										if($redirect == true){
											if($multilanguage == true) {
												echo $lang['form-wait-message'];
												redirecttime($emailcopyerror2);																
											} else {
												echo $lang['form-wait-message'];
												redirecttime($emailcopyerror1);
											}
										} else {
											echo $lang['form-email-copy-message'];
										}
									}
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											redirecttime($mailerror2);																
										} else {
											echo $lang['form-wait-message'];
											redirecttime($mailerror1);
										}
									} else {
										echo $lang['form-mail-message'];
									}
								}
							} else {
								if($redirect == true){
									if($multilanguage == true) {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror2);																
									} else {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror1);
									}
								} else {
									echo $lang['form-transaction-message'];
								}										
							}
						} else {
							if($redirect == true){
								if($multilanguage == true) {
									echo $lang['form-wait-message'];
									redirecttime($customererror2);																
								} else {
									echo $lang['form-wait-message'];
									redirecttime($customererror1);
								}
							} else {
								echo $lang['form-customer-message'];
							}										
						}
					}
				}
			}
			
		} else {
			
			if($automessage == true){
	
				if ($mysql == true){
					
					if($signature == true){
										
						$paypalcustomer = Braintree_Customer::create(array(
							'id' => $finalcustomerid,
							'firstName' => $finalfirstname,
							'lastName' => $finallastname,
							'email' => $finalemail,
							'paymentMethodNonce' => $finalnonce
						));
					
						if($paypalcustomer->success){
							
							$finalpaypaltoken = $paypalcustomer->customer->paymentMethods[0]->token;
																	
							$paypaltransaction = Braintree_Transaction::sale(array(
								'amount' => $finalpaymentprice,
								'paymentMethodToken' => $finalpaypaltoken, 
								'options' => array(
									'submitForSettlement' => $submitforsettlement,
									'storeInVault' => $storeinvault
								)
							));
						
							if($paypaltransaction->success){
								
								include 'functions/signature/signature.php';
								
								if($adminemail->send()){
								
									include 'messages/customer/automessage-payment.php';
									include 'automessage.php';
								
									if($automail->send()){
										if($sendmobile == true){
											if($redirect == true){
												if($multilanguage == true) {
													$mobile->send($notification, $phone, $unicode);
													echo $lang['form-wait-message'];
													db_insert_payment($tablenamepayment,$insertpaymentvalues);	
													redirecttime($paymentsuccess2);
												} else {
													$mobile->send($notification, $phone, $unicode);
													echo $lang['form-wait-message'];
													db_insert_payment($tablenamepayment,$insertpaymentvalues);	
													redirecttime($paymentsuccess1);
												}
											} else {
												$mobile->send($notification, $phone, $unicode);
												db_insert_payment($tablenamepayment,$insertpaymentvalues);
												echo $lang['form-success-payment-message'];
											}
										} else {
											if($redirect == true){
												if($multilanguage == true) {
													echo $lang['form-wait-message'];
													db_insert_payment($tablenamepayment,$insertpaymentvalues);	
													redirecttime($paymentsuccess2);
												} else {
													echo $lang['form-wait-message'];
													db_insert_payment($tablenamepayment,$insertpaymentvalues);	
													redirecttime($paymentsuccess1);
												}
											} else {														
												db_insert_payment($tablenamepayment,$insertpaymentvalues);	
												echo $lang['form-success-payment-message'];
											}
										}
									} else {
										if($redirect == true){
											if($multilanguage == true) {
												echo $lang['form-wait-message'];
												redirecttime($automailerror2);																
											} else {
												echo $lang['form-wait-message'];
												redirecttime($automailerror1);
											}
										} else {
											echo $lang['form-automessage-message'];
										}
									}	
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											redirecttime($mailerror2);																
										} else {
											echo $lang['form-wait-message'];
											redirecttime($mailerror1);
										}
									} else {
										echo $lang['form-mail-message'];
									}
								}
							} else {
								if($redirect == true){
									if($multilanguage == true) {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror2);																
									} else {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror1);
									}
								} else {
									echo $lang['form-transaction-message'];
								}										
							}
						} else {
							if($redirect == true){
								if($multilanguage == true) {
									echo $lang['form-wait-message'];
									redirecttime($customererror2);																
								} else {
									echo $lang['form-wait-message'];
									redirecttime($customererror1);
								}
							} else {
								echo $lang['form-customer-message'];
							}										
						}
						
					} else {
						
						$paypalcustomer = Braintree_Customer::create(array(
							'id' => $finalcustomerid,
							'firstName' => $finalfirstname,
							'lastName' => $finallastname,
							'email' => $finalemail,
							'paymentMethodNonce' => $finalnonce
						));
					
						if($paypalcustomer->success){
							
							$finalpaypaltoken = $paypalcustomer->customer->paymentMethods[0]->token;
																	
							$paypaltransaction = Braintree_Transaction::sale(array(
								'amount' => $finalpaymentprice,
								'paymentMethodToken' => $finalpaypaltoken, 
								'options' => array(
									'submitForSettlement' => $submitforsettlement,
									'storeInVault' => $storeinvault
								)
							));
						
							if($paypaltransaction->success){
																																									
								if($adminemail->send()){
								
									include 'messages/customer/automessage-payment.php';
									include 'automessage.php';
								
									if($automail->send()){
										if($sendmobile == true){
											if($redirect == true){
												if($multilanguage == true) {
													$mobile->send($notification, $phone, $unicode);
													echo $lang['form-wait-message'];
													db_insert_payment($tablenamepayment,$insertpaymentvalues);	
													redirecttime($paymentsuccess2);
												} else {
													$mobile->send($notification, $phone, $unicode);
													echo $lang['form-wait-message'];
													db_insert_payment($tablenamepayment,$insertpaymentvalues);	
													redirecttime($paymentsuccess1);
												}
											} else {
												$mobile->send($notification, $phone, $unicode);
												db_insert_payment($tablenamepayment,$insertpaymentvalues);
												echo $lang['form-success-payment-message'];
											}
										} else {
											if($redirect == true){
												if($multilanguage == true) {
													echo $lang['form-wait-message'];
													db_insert_payment($tablenamepayment,$insertpaymentvalues);	
													redirecttime($paymentsuccess2);
												} else {
													echo $lang['form-wait-message'];
													db_insert_payment($tablenamepayment,$insertpaymentvalues);	
													redirecttime($paymentsuccess1);
												}
											} else {														
												db_insert_payment($tablenamepayment,$insertpaymentvalues);	
												echo $lang['form-success-payment-message'];
											}
										}
									} else {
										if($redirect == true){
											if($multilanguage == true) {
												echo $lang['form-wait-message'];
												redirecttime($automailerror2);																
											} else {
												echo $lang['form-wait-message'];
												redirecttime($automailerror1);
											}
										} else {
											echo $lang['form-automessage-message'];
										}
									}	
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											redirecttime($mailerror2);																
										} else {
											echo $lang['form-wait-message'];
											redirecttime($mailerror1);
										}
									} else {
										echo $lang['form-mail-message'];
									}
								}
							} else {
								if($redirect == true){
									if($multilanguage == true) {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror2);																
									} else {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror1);
									}
								} else {
									echo $lang['form-transaction-message'];
								}										
							}
						} else {
							if($redirect == true){
								if($multilanguage == true) {
									echo $lang['form-wait-message'];
									redirecttime($customererror2);																
								} else {
									echo $lang['form-wait-message'];
									redirecttime($customererror1);
								}
							} else {
								echo $lang['form-customer-message'];
							}										
						}
					}
					
				} else {
					
					if($signature == true){
										
						$paypalcustomer = Braintree_Customer::create(array(
							'id' => $finalcustomerid,
							'firstName' => $finalfirstname,
							'lastName' => $finallastname,
							'email' => $finalemail,
							'paymentMethodNonce' => $finalnonce
						));
					
						if($paypalcustomer->success){
							
							$finalpaypaltoken = $paypalcustomer->customer->paymentMethods[0]->token;
																	
							$paypaltransaction = Braintree_Transaction::sale(array(
								'amount' => $finalpaymentprice,
								'paymentMethodToken' => $finalpaypaltoken, 
								'options' => array(
									'submitForSettlement' => $submitforsettlement,
									'storeInVault' => $storeinvault
								)
							));
						
							if($paypaltransaction->success){
								
								include 'functions/signature/signature.php';
								
								if($adminemail->send()){
								
									include 'messages/customer/automessage-payment.php';
									include 'automessage.php';
								
									if($automail->send()){
										if($sendmobile == true){
											if($redirect == true){
												if($multilanguage == true) {
													$mobile->send($notification, $phone, $unicode);
													echo $lang['form-wait-message'];
													redirecttime($paymentsuccess2);
												} else {
													$mobile->send($notification, $phone, $unicode);
													echo $lang['form-wait-message'];
													redirecttime($paymentsuccess1);
												}
											} else {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-success-payment-message'];
											}
										} else {
											if($redirect == true){
												if($multilanguage == true) {
													echo $lang['form-wait-message'];
													redirecttime($paymentsuccess2);
												} else {
													echo $lang['form-wait-message'];
													redirecttime($paymentsuccess1);
												}
											} else {														
												echo $lang['form-success-payment-message'];
											}
										}
									} else {
										if($redirect == true){
											if($multilanguage == true) {
												echo $lang['form-wait-message'];
												redirecttime($automailerror2);																
											} else {
												echo $lang['form-wait-message'];
												redirecttime($automailerror1);
											}
										} else {
											echo $lang['form-automessage-message'];
										}
									}	
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											redirecttime($mailerror2);																
										} else {
											echo $lang['form-wait-message'];
											redirecttime($mailerror1);
										}
									} else {
										echo $lang['form-mail-message'];
									}
								}
							} else {
								if($redirect == true){
									if($multilanguage == true) {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror2);																
									} else {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror1);
									}
								} else {
									echo $lang['form-transaction-message'];
								}										
							}
						} else {
							if($redirect == true){
								if($multilanguage == true) {
									echo $lang['form-wait-message'];
									redirecttime($customererror2);																
								} else {
									echo $lang['form-wait-message'];
									redirecttime($customererror1);
								}
							} else {
								echo $lang['form-customer-message'];
							}										
						}
						
					} else {
						
						$paypalcustomer = Braintree_Customer::create(array(
							'id' => $finalcustomerid,
							'firstName' => $finalfirstname,
							'lastName' => $finallastname,
							'email' => $finalemail,
							'paymentMethodNonce' => $finalnonce
						));
					
						if($paypalcustomer->success){
							
							$finalpaypaltoken = $paypalcustomer->customer->paymentMethods[0]->token;
																	
							$paypaltransaction = Braintree_Transaction::sale(array(
								'amount' => $finalpaymentprice,
								'paymentMethodToken' => $finalpaypaltoken, 
								'options' => array(
									'submitForSettlement' => $submitforsettlement,
									'storeInVault' => $storeinvault
								)
							));
						
							if($paypaltransaction->success){
																																									
								if($adminemail->send()){
								
									include 'messages/customer/automessage-payment.php';
									include 'automessage.php';
								
									if($automail->send()){
										if($sendmobile == true){
											if($redirect == true){
												if($multilanguage == true) {
													$mobile->send($notification, $phone, $unicode);
													echo $lang['form-wait-message'];
													redirecttime($paymentsuccess2);
												} else {
													$mobile->send($notification, $phone, $unicode);
													echo $lang['form-wait-message'];
													redirecttime($paymentsuccess1);
												}
											} else {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-success-payment-message'];
											}
										} else {
											if($redirect == true){
												if($multilanguage == true) {
													echo $lang['form-wait-message'];
													redirecttime($paymentsuccess2);
												} else {
													echo $lang['form-wait-message'];
													redirecttime($paymentsuccess1);
												}
											} else {														
												echo $lang['form-success-payment-message'];
											}
										}
									} else {
										if($redirect == true){
											if($multilanguage == true) {
												echo $lang['form-wait-message'];
												redirecttime($automailerror2);																
											} else {
												echo $lang['form-wait-message'];
												redirecttime($automailerror1);
											}
										} else {
											echo $lang['form-automessage-message'];
										}
									}	
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											redirecttime($mailerror2);																
										} else {
											echo $lang['form-wait-message'];
											redirecttime($mailerror1);
										}
									} else {
										echo $lang['form-mail-message'];
									}
								}
							} else {
								if($redirect == true){
									if($multilanguage == true) {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror2);																
									} else {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror1);
									}
								} else {
									echo $lang['form-transaction-message'];
								}										
							}
						} else {
							if($redirect == true){
								if($multilanguage == true) {
									echo $lang['form-wait-message'];
									redirecttime($customererror2);																
								} else {
									echo $lang['form-wait-message'];
									redirecttime($customererror1);
								}
							} else {
								echo $lang['form-customer-message'];
							}										
						}
					}
				}
				
			} else {
				
				if ($mysql == true){
					
					if($signature == true){
										
						$paypalcustomer = Braintree_Customer::create(array(
							'id' => $finalcustomerid,
							'firstName' => $finalfirstname,
							'lastName' => $finallastname,
							'email' => $finalemail,
							'paymentMethodNonce' => $finalnonce
						));
					
						if($paypalcustomer->success){
							
							$finalpaypaltoken = $paypalcustomer->customer->paymentMethods[0]->token;
																	
							$paypaltransaction = Braintree_Transaction::sale(array(
								'amount' => $finalpaymentprice,
								'paymentMethodToken' => $finalpaypaltoken, 
								'options' => array(
									'submitForSettlement' => $submitforsettlement,
									'storeInVault' => $storeinvault
								)
							));
						
							if($paypaltransaction->success){
								
								include 'functions/signature/signature.php';
								
								if($adminemail->send()){
									if($sendmobile == true){
										if($redirect == true){
											if($multilanguage == true) {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-wait-message'];
												db_insert_payment($tablenamepayment,$insertpaymentvalues);	
												redirecttime($paymentsuccess2);
											} else {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-wait-message'];
												db_insert_payment($tablenamepayment,$insertpaymentvalues);	
												redirecttime($paymentsuccess1);
											}
										} else {
											$mobile->send($notification, $phone, $unicode);
											db_insert_payment($tablenamepayment,$insertpaymentvalues);
											echo $lang['form-success-payment-message'];
										}
									} else {
										if($redirect == true){
											if($multilanguage == true) {
												echo $lang['form-wait-message'];
												db_insert_payment($tablenamepayment,$insertpaymentvalues);	
												redirecttime($paymentsuccess2);
											} else {
												echo $lang['form-wait-message'];
												db_insert_payment($tablenamepayment,$insertpaymentvalues);	
												redirecttime($paymentsuccess1);
											}
										} else {														
											db_insert_payment($tablenamepayment,$insertpaymentvalues);	
											echo $lang['form-success-payment-message'];
										}
									}
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											redirecttime($mailerror2);																
										} else {
											echo $lang['form-wait-message'];
											redirecttime($mailerror1);
										}
									} else {
										echo $lang['form-mail-message'];
									}
								}
							} else {
								if($redirect == true){
									if($multilanguage == true) {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror2);																
									} else {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror1);
									}
								} else {
									echo $lang['form-transaction-message'];
								}										
							}
						} else {
							if($redirect == true){
								if($multilanguage == true) {
									echo $lang['form-wait-message'];
									redirecttime($customererror2);																
								} else {
									echo $lang['form-wait-message'];
									redirecttime($customererror1);
								}
							} else {
								echo $lang['form-customer-message'];
							}										
						}
						
					} else {
						
						$paypalcustomer = Braintree_Customer::create(array(
							'id' => $finalcustomerid,
							'firstName' => $finalfirstname,
							'lastName' => $finallastname,
							'email' => $finalemail,
							'paymentMethodNonce' => $finalnonce
						));
					
						if($paypalcustomer->success){
							
							$finalpaypaltoken = $paypalcustomer->customer->paymentMethods[0]->token;
																	
							$paypaltransaction = Braintree_Transaction::sale(array(
								'amount' => $finalpaymentprice,
								'paymentMethodToken' => $finalpaypaltoken, 
								'options' => array(
									'submitForSettlement' => $submitforsettlement,
									'storeInVault' => $storeinvault
								)
							));
						
							if($paypaltransaction->success){
																																									
								if($adminemail->send()){
									if($sendmobile == true){
										if($redirect == true){
											if($multilanguage == true) {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-wait-message'];
												db_insert_payment($tablenamepayment,$insertpaymentvalues);	
												redirecttime($paymentsuccess2);
											} else {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-wait-message'];
												db_insert_payment($tablenamepayment,$insertpaymentvalues);	
												redirecttime($paymentsuccess1);
											}
										} else {
											$mobile->send($notification, $phone, $unicode);
											db_insert_payment($tablenamepayment,$insertpaymentvalues);
											echo $lang['form-success-payment-message'];
										}
									} else {
										if($redirect == true){
											if($multilanguage == true) {
												echo $lang['form-wait-message'];
												db_insert_payment($tablenamepayment,$insertpaymentvalues);	
												redirecttime($paymentsuccess2);
											} else {
												echo $lang['form-wait-message'];
												db_insert_payment($tablenamepayment,$insertpaymentvalues);	
												redirecttime($paymentsuccess1);
											}
										} else {														
											db_insert_payment($tablenamepayment,$insertpaymentvalues);	
											echo $lang['form-success-payment-message'];
										}
									}
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											redirecttime($mailerror2);																
										} else {
											echo $lang['form-wait-message'];
											redirecttime($mailerror1);
										}
									} else {
										echo $lang['form-mail-message'];
									}
								}
							} else {
								if($redirect == true){
									if($multilanguage == true) {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror2);																
									} else {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror1);
									}
								} else {
									echo $lang['form-transaction-message'];
								}										
							}
						} else {
							if($redirect == true){
								if($multilanguage == true) {
									echo $lang['form-wait-message'];
									redirecttime($customererror2);																
								} else {
									echo $lang['form-wait-message'];
									redirecttime($customererror1);
								}
							} else {
								echo $lang['form-customer-message'];
							}										
						}
					}
					
				} else {
					
					if($signature == true){
										
						$paypalcustomer = Braintree_Customer::create(array(
							'id' => $finalcustomerid,
							'firstName' => $finalfirstname,
							'lastName' => $finallastname,
							'email' => $finalemail,
							'paymentMethodNonce' => $finalnonce
						));
					
						if($paypalcustomer->success){
							
							$finalpaypaltoken = $paypalcustomer->customer->paymentMethods[0]->token;
																	
							$paypaltransaction = Braintree_Transaction::sale(array(
								'amount' => $finalpaymentprice,
								'paymentMethodToken' => $finalpaypaltoken, 
								'options' => array(
									'submitForSettlement' => $submitforsettlement,
									'storeInVault' => $storeinvault
								)
							));
						
							if($paypaltransaction->success){
								
								include 'functions/signature/signature.php';
								
								if($adminemail->send()){
									if($sendmobile == true){
										if($redirect == true){
											if($multilanguage == true) {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-wait-message'];
												redirecttime($paymentsuccess2);
											} else {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-wait-message'];
												redirecttime($paymentsuccess1);
											}
										} else {
											$mobile->send($notification, $phone, $unicode);
											echo $lang['form-success-payment-message'];
										}
									} else {
										if($redirect == true){
											if($multilanguage == true) {
												echo $lang['form-wait-message'];
												redirecttime($paymentsuccess2);
											} else {
												echo $lang['form-wait-message'];
												redirecttime($paymentsuccess1);
											}
										} else {														
											echo $lang['form-success-payment-message'];
										}
									}
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											redirecttime($mailerror2);																
										} else {
											echo $lang['form-wait-message'];
											redirecttime($mailerror1);
										}
									} else {
										echo $lang['form-mail-message'];
									}
								}
							} else {
								if($redirect == true){
									if($multilanguage == true) {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror2);																
									} else {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror1);
									}
								} else {
									echo $lang['form-transaction-message'];
								}										
							}
						} else {
							if($redirect == true){
								if($multilanguage == true) {
									echo $lang['form-wait-message'];
									redirecttime($customererror2);																
								} else {
									echo $lang['form-wait-message'];
									redirecttime($customererror1);
								}
							} else {
								echo $lang['form-customer-message'];
							}										
						}
						
					} else {
						
						$paypalcustomer = Braintree_Customer::create(array(
							'id' => $finalcustomerid,
							'firstName' => $finalfirstname,
							'lastName' => $finallastname,
							'email' => $finalemail,
							'paymentMethodNonce' => $finalnonce
						));
					
						if($paypalcustomer->success){
							
							$finalpaypaltoken = $paypalcustomer->customer->paymentMethods[0]->token;
																	
							$paypaltransaction = Braintree_Transaction::sale(array(
								'amount' => $finalpaymentprice,
								'paymentMethodToken' => $finalpaypaltoken, 
								'options' => array(
									'submitForSettlement' => $submitforsettlement,
									'storeInVault' => $storeinvault
								)
							));
						
							if($paypaltransaction->success){
																																									
								if($adminemail->send()){
									if($sendmobile == true){
										if($redirect == true){
											if($multilanguage == true) {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-wait-message'];
												redirecttime($paymentsuccess2);
											} else {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-wait-message'];
												redirecttime($paymentsuccess1);
											}
										} else {
											$mobile->send($notification, $phone, $unicode);
											echo $lang['form-success-payment-message'];
										}
									} else {
										if($redirect == true){
											if($multilanguage == true) {
												echo $lang['form-wait-message'];
												redirecttime($paymentsuccess2);
											} else {
												echo $lang['form-wait-message'];
												redirecttime($paymentsuccess1);
											}
										} else {														
											echo $lang['form-success-payment-message'];
										}
									}
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											redirecttime($mailerror2);																
										} else {
											echo $lang['form-wait-message'];
											redirecttime($mailerror1);
										}
									} else {
										echo $lang['form-mail-message'];
									}
								}
							} else {
								if($redirect == true){
									if($multilanguage == true) {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror2);																
									} else {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror1);
									}
								} else {
									echo $lang['form-transaction-message'];
								}										
							}
						} else {
							if($redirect == true){
								if($multilanguage == true) {
									echo $lang['form-wait-message'];
									redirecttime($customererror2);																
								} else {
									echo $lang['form-wait-message'];
									redirecttime($customererror1);
								}
							} else {
								echo $lang['form-customer-message'];
							}										
						}
					}
				}
			}
		}
		
	} else {
		
		if($automessage == true){
	
			if ($mysql == true){
				
				if($signature == true){
									
					$paypalcustomer = Braintree_Customer::create(array(
						'id' => $finalcustomerid,
						'firstName' => $finalfirstname,
						'lastName' => $finallastname,
						'email' => $finalemail,
						'paymentMethodNonce' => $finalnonce
					));
				
					if($paypalcustomer->success){
						
						$finalpaypaltoken = $paypalcustomer->customer->paymentMethods[0]->token;
																
						$paypaltransaction = Braintree_Transaction::sale(array(
							'amount' => $finalpaymentprice,
							'paymentMethodToken' => $finalpaypaltoken, 
							'options' => array(
								'submitForSettlement' => $submitforsettlement,
								'storeInVault' => $storeinvault
							)
						));
					
						if($paypaltransaction->success){
							
							include 'functions/signature/signature.php';
							
							if($adminemail->send()){
							
								include 'messages/customer/automessage-payment.php';
								include 'automessage.php';
							
								if($automail->send()){
									if($sendmobile == true){
										if($redirect == true){
											if($multilanguage == true) {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-wait-message'];
												db_insert_payment($tablenamepayment,$insertpaymentvalues);	
												redirecttime($paymentsuccess2);
											} else {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-wait-message'];
												db_insert_payment($tablenamepayment,$insertpaymentvalues);	
												redirecttime($paymentsuccess1);
											}
										} else {
											$mobile->send($notification, $phone, $unicode);
											db_insert_payment($tablenamepayment,$insertpaymentvalues);
											echo $lang['form-success-payment-message'];
										}
									} else {
										if($redirect == true){
											if($multilanguage == true) {
												echo $lang['form-wait-message'];
												db_insert_payment($tablenamepayment,$insertpaymentvalues);	
												redirecttime($paymentsuccess2);
											} else {
												echo $lang['form-wait-message'];
												db_insert_payment($tablenamepayment,$insertpaymentvalues);	
												redirecttime($paymentsuccess1);
											}
										} else {														
											db_insert_payment($tablenamepayment,$insertpaymentvalues);	
											echo $lang['form-success-payment-message'];
										}
									}
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											redirecttime($automailerror2);																
										} else {
											echo $lang['form-wait-message'];
											redirecttime($automailerror1);
										}
									} else {
										echo $lang['form-automessage-message'];
									}
								}	
							} else {
								if($redirect == true){
									if($multilanguage == true) {
										echo $lang['form-wait-message'];
										redirecttime($mailerror2);																
									} else {
										echo $lang['form-wait-message'];
										redirecttime($mailerror1);
									}
								} else {
									echo $lang['form-mail-message'];
								}
							}
						} else {
							if($redirect == true){
								if($multilanguage == true) {
									echo $lang['form-wait-message'];
									redirecttime($transactionerror2);																
								} else {
									echo $lang['form-wait-message'];
									redirecttime($transactionerror1);
								}
							} else {
								echo $lang['form-transaction-message'];
							}										
						}
					} else {
						if($redirect == true){
							if($multilanguage == true) {
								echo $lang['form-wait-message'];
								redirecttime($customererror2);																
							} else {
								echo $lang['form-wait-message'];
								redirecttime($customererror1);
							}
						} else {
							echo $lang['form-customer-message'];
						}										
					}
					
				} else {
					
					$paypalcustomer = Braintree_Customer::create(array(
						'id' => $finalcustomerid,
						'firstName' => $finalfirstname,
						'lastName' => $finallastname,
						'email' => $finalemail,
						'paymentMethodNonce' => $finalnonce
					));
				
					if($paypalcustomer->success){
						
						$finalpaypaltoken = $paypalcustomer->customer->paymentMethods[0]->token;
																
						$paypaltransaction = Braintree_Transaction::sale(array(
							'amount' => $finalpaymentprice,
							'paymentMethodToken' => $finalpaypaltoken, 
							'options' => array(
								'submitForSettlement' => $submitforsettlement,
								'storeInVault' => $storeinvault
							)
						));
					
						if($paypaltransaction->success){
																																								
							if($adminemail->send()){
							
								include 'messages/customer/automessage-payment.php';
								include 'automessage.php';
							
								if($automail->send()){
									if($sendmobile == true){
										if($redirect == true){
											if($multilanguage == true) {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-wait-message'];
												db_insert_payment($tablenamepayment,$insertpaymentvalues);	
												redirecttime($paymentsuccess2);
											} else {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-wait-message'];
												db_insert_payment($tablenamepayment,$insertpaymentvalues);	
												redirecttime($paymentsuccess1);
											}
										} else {
											$mobile->send($notification, $phone, $unicode);
											db_insert_payment($tablenamepayment,$insertpaymentvalues);
											echo $lang['form-success-payment-message'];
										}
									} else {
										if($redirect == true){
											if($multilanguage == true) {
												echo $lang['form-wait-message'];
												db_insert_payment($tablenamepayment,$insertpaymentvalues);	
												redirecttime($paymentsuccess2);
											} else {
												echo $lang['form-wait-message'];
												db_insert_payment($tablenamepayment,$insertpaymentvalues);	
												redirecttime($paymentsuccess1);
											}
										} else {														
											db_insert_payment($tablenamepayment,$insertpaymentvalues);	
											echo $lang['form-success-payment-message'];
										}
									}
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											redirecttime($automailerror2);																
										} else {
											echo $lang['form-wait-message'];
											redirecttime($automailerror1);
										}
									} else {
										echo $lang['form-automessage-message'];
									}
								}	
							} else {
								if($redirect == true){
									if($multilanguage == true) {
										echo $lang['form-wait-message'];
										redirecttime($mailerror2);																
									} else {
										echo $lang['form-wait-message'];
										redirecttime($mailerror1);
									}
								} else {
									echo $lang['form-mail-message'];
								}
							}
						} else {
							if($redirect == true){
								if($multilanguage == true) {
									echo $lang['form-wait-message'];
									redirecttime($transactionerror2);																
								} else {
									echo $lang['form-wait-message'];
									redirecttime($transactionerror1);
								}
							} else {
								echo $lang['form-transaction-message'];
							}										
						}
					} else {
						if($redirect == true){
							if($multilanguage == true) {
								echo $lang['form-wait-message'];
								redirecttime($customererror2);																
							} else {
								echo $lang['form-wait-message'];
								redirecttime($customererror1);
							}
						} else {
							echo $lang['form-customer-message'];
						}										
					}
				}
				
			} else {
				
				if($signature == true){
									
					$paypalcustomer = Braintree_Customer::create(array(
						'id' => $finalcustomerid,
						'firstName' => $finalfirstname,
						'lastName' => $finallastname,
						'email' => $finalemail,
						'paymentMethodNonce' => $finalnonce
					));
				
					if($paypalcustomer->success){
						
						$finalpaypaltoken = $paypalcustomer->customer->paymentMethods[0]->token;
																
						$paypaltransaction = Braintree_Transaction::sale(array(
							'amount' => $finalpaymentprice,
							'paymentMethodToken' => $finalpaypaltoken, 
							'options' => array(
								'submitForSettlement' => $submitforsettlement,
								'storeInVault' => $storeinvault
							)
						));
					
						if($paypaltransaction->success){
							
							include 'functions/signature/signature.php';
							
							if($adminemail->send()){
							
								include 'messages/customer/automessage-payment.php';
								include 'automessage.php';
							
								if($automail->send()){
									if($sendmobile == true){
										if($redirect == true){
											if($multilanguage == true) {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-wait-message'];
												redirecttime($paymentsuccess2);
											} else {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-wait-message'];
												redirecttime($paymentsuccess1);
											}
										} else {
											$mobile->send($notification, $phone, $unicode);
											echo $lang['form-success-payment-message'];
										}
									} else {
										if($redirect == true){
											if($multilanguage == true) {
												echo $lang['form-wait-message'];
												redirecttime($paymentsuccess2);
											} else {
												echo $lang['form-wait-message'];
												redirecttime($paymentsuccess1);
											}
										} else {														
											echo $lang['form-success-payment-message'];
										}
									}
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											redirecttime($automailerror2);																
										} else {
											echo $lang['form-wait-message'];
											redirecttime($automailerror1);
										}
									} else {
										echo $lang['form-automessage-message'];
									}
								}	
							} else {
								if($redirect == true){
									if($multilanguage == true) {
										echo $lang['form-wait-message'];
										redirecttime($mailerror2);																
									} else {
										echo $lang['form-wait-message'];
										redirecttime($mailerror1);
									}
								} else {
									echo $lang['form-mail-message'];
								}
							}
						} else {
							if($redirect == true){
								if($multilanguage == true) {
									echo $lang['form-wait-message'];
									redirecttime($transactionerror2);																
								} else {
									echo $lang['form-wait-message'];
									redirecttime($transactionerror1);
								}
							} else {
								echo $lang['form-transaction-message'];
							}										
						}
					} else {
						if($redirect == true){
							if($multilanguage == true) {
								echo $lang['form-wait-message'];
								redirecttime($customererror2);																
							} else {
								echo $lang['form-wait-message'];
								redirecttime($customererror1);
							}
						} else {
							echo $lang['form-customer-message'];
						}										
					}
					
				} else {
					
					$paypalcustomer = Braintree_Customer::create(array(
						'id' => $finalcustomerid,
						'firstName' => $finalfirstname,
						'lastName' => $finallastname,
						'email' => $finalemail,
						'paymentMethodNonce' => $finalnonce
					));
				
					if($paypalcustomer->success){
						
						$finalpaypaltoken = $paypalcustomer->customer->paymentMethods[0]->token;
																
						$paypaltransaction = Braintree_Transaction::sale(array(
							'amount' => $finalpaymentprice,
							'paymentMethodToken' => $finalpaypaltoken, 
							'options' => array(
								'submitForSettlement' => $submitforsettlement,
								'storeInVault' => $storeinvault
							)
						));
					
						if($paypaltransaction->success){
																																								
							if($adminemail->send()){
							
								include 'messages/customer/automessage-payment.php';
								include 'automessage.php';
							
								if($automail->send()){
									if($sendmobile == true){
										if($redirect == true){
											if($multilanguage == true) {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-wait-message'];
												redirecttime($paymentsuccess2);
											} else {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-wait-message'];
												redirecttime($paymentsuccess1);
											}
										} else {
											$mobile->send($notification, $phone, $unicode);
											echo $lang['form-success-payment-message'];
										}
									} else {
										if($redirect == true){
											if($multilanguage == true) {
												echo $lang['form-wait-message'];
												redirecttime($paymentsuccess2);
											} else {
												echo $lang['form-wait-message'];
												redirecttime($paymentsuccess1);
											}
										} else {														
											echo $lang['form-success-payment-message'];
										}
									}
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											redirecttime($automailerror2);																
										} else {
											echo $lang['form-wait-message'];
											redirecttime($automailerror1);
										}
									} else {
										echo $lang['form-automessage-message'];
									}
								}	
							} else {
								if($redirect == true){
									if($multilanguage == true) {
										echo $lang['form-wait-message'];
										redirecttime($mailerror2);																
									} else {
										echo $lang['form-wait-message'];
										redirecttime($mailerror1);
									}
								} else {
									echo $lang['form-mail-message'];
								}
							}
						} else {
							if($redirect == true){
								if($multilanguage == true) {
									echo $lang['form-wait-message'];
									redirecttime($transactionerror2);																
								} else {
									echo $lang['form-wait-message'];
									redirecttime($transactionerror1);
								}
							} else {
								echo $lang['form-transaction-message'];
							}										
						}
					} else {
						if($redirect == true){
							if($multilanguage == true) {
								echo $lang['form-wait-message'];
								redirecttime($customererror2);																
							} else {
								echo $lang['form-wait-message'];
								redirecttime($customererror1);
							}
						} else {
							echo $lang['form-customer-message'];
						}										
					}
				}
			}
			
		} else {
			
			if ($mysql == true){
				
				if($signature == true){
									
					$paypalcustomer = Braintree_Customer::create(array(
						'id' => $finalcustomerid,
						'firstName' => $finalfirstname,
						'lastName' => $finallastname,
						'email' => $finalemail,
						'paymentMethodNonce' => $finalnonce
					));
				
					if($paypalcustomer->success){
						
						$finalpaypaltoken = $paypalcustomer->customer->paymentMethods[0]->token;
																
						$paypaltransaction = Braintree_Transaction::sale(array(
							'amount' => $finalpaymentprice,
							'paymentMethodToken' => $finalpaypaltoken, 
							'options' => array(
								'submitForSettlement' => $submitforsettlement,
								'storeInVault' => $storeinvault
							)
						));
					
						if($paypaltransaction->success){
							
							include 'functions/signature/signature.php';
							
							if($adminemail->send()){
								if($sendmobile == true){
									if($redirect == true){
										if($multilanguage == true) {
											$mobile->send($notification, $phone, $unicode);
											echo $lang['form-wait-message'];
											db_insert_payment($tablenamepayment,$insertpaymentvalues);	
											redirecttime($paymentsuccess2);
										} else {
											$mobile->send($notification, $phone, $unicode);
											echo $lang['form-wait-message'];
											db_insert_payment($tablenamepayment,$insertpaymentvalues);	
											redirecttime($paymentsuccess1);
										}
									} else {
										$mobile->send($notification, $phone, $unicode);
										db_insert_payment($tablenamepayment,$insertpaymentvalues);
										echo $lang['form-success-payment-message'];
									}
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											db_insert_payment($tablenamepayment,$insertpaymentvalues);	
											redirecttime($paymentsuccess2);
										} else {
											echo $lang['form-wait-message'];
											db_insert_payment($tablenamepayment,$insertpaymentvalues);	
											redirecttime($paymentsuccess1);
										}
									} else {														
										db_insert_payment($tablenamepayment,$insertpaymentvalues);	
										echo $lang['form-success-payment-message'];
									}
								}
							} else {
								if($redirect == true){
									if($multilanguage == true) {
										echo $lang['form-wait-message'];
										redirecttime($mailerror2);																
									} else {
										echo $lang['form-wait-message'];
										redirecttime($mailerror1);
									}
								} else {
									echo $lang['form-mail-message'];
								}
							}
						} else {
							if($redirect == true){
								if($multilanguage == true) {
									echo $lang['form-wait-message'];
									redirecttime($transactionerror2);																
								} else {
									echo $lang['form-wait-message'];
									redirecttime($transactionerror1);
								}
							} else {
								echo $lang['form-transaction-message'];
							}										
						}
					} else {
						if($redirect == true){
							if($multilanguage == true) {
								echo $lang['form-wait-message'];
								redirecttime($customererror2);																
							} else {
								echo $lang['form-wait-message'];
								redirecttime($customererror1);
							}
						} else {
							echo $lang['form-customer-message'];
						}										
					}
					
				} else {
					
					$paypalcustomer = Braintree_Customer::create(array(
						'id' => $finalcustomerid,
						'firstName' => $finalfirstname,
						'lastName' => $finallastname,
						'email' => $finalemail,
						'paymentMethodNonce' => $finalnonce
					));
				
					if($paypalcustomer->success){
						
						$finalpaypaltoken = $paypalcustomer->customer->paymentMethods[0]->token;
																
						$paypaltransaction = Braintree_Transaction::sale(array(
							'amount' => $finalpaymentprice,
							'paymentMethodToken' => $finalpaypaltoken, 
							'options' => array(
								'submitForSettlement' => $submitforsettlement,
								'storeInVault' => $storeinvault
							)
						));
					
						if($paypaltransaction->success){
																																								
							if($adminemail->send()){
								if($sendmobile == true){
									if($redirect == true){
										if($multilanguage == true) {
											$mobile->send($notification, $phone, $unicode);
											echo $lang['form-wait-message'];
											db_insert_payment($tablenamepayment,$insertpaymentvalues);	
											redirecttime($paymentsuccess2);
										} else {
											$mobile->send($notification, $phone, $unicode);
											echo $lang['form-wait-message'];
											db_insert_payment($tablenamepayment,$insertpaymentvalues);	
											redirecttime($paymentsuccess1);
										}
									} else {
										$mobile->send($notification, $phone, $unicode);
										db_insert_payment($tablenamepayment,$insertpaymentvalues);
										echo $lang['form-success-payment-message'];
									}
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											db_insert_payment($tablenamepayment,$insertpaymentvalues);	
											redirecttime($paymentsuccess2);
										} else {
											echo $lang['form-wait-message'];
											db_insert_payment($tablenamepayment,$insertpaymentvalues);	
											redirecttime($paymentsuccess1);
										}
									} else {														
										db_insert_payment($tablenamepayment,$insertpaymentvalues);	
										echo $lang['form-success-payment-message'];
									}
								}
							} else {
								if($redirect == true){
									if($multilanguage == true) {
										echo $lang['form-wait-message'];
										redirecttime($mailerror2);																
									} else {
										echo $lang['form-wait-message'];
										redirecttime($mailerror1);
									}
								} else {
									echo $lang['form-mail-message'];
								}
							}
						} else {
							if($redirect == true){
								if($multilanguage == true) {
									echo $lang['form-wait-message'];
									redirecttime($transactionerror2);																
								} else {
									echo $lang['form-wait-message'];
									redirecttime($transactionerror1);
								}
							} else {
								echo $lang['form-transaction-message'];
							}										
						}
					} else {
						if($redirect == true){
							if($multilanguage == true) {
								echo $lang['form-wait-message'];
								redirecttime($customererror2);																
							} else {
								echo $lang['form-wait-message'];
								redirecttime($customererror1);
							}
						} else {
							echo $lang['form-customer-message'];
						}										
					}
				}
				
			} else {
				
				if($signature == true){
									
					$paypalcustomer = Braintree_Customer::create(array(
						'id' => $finalcustomerid,
						'firstName' => $finalfirstname,
						'lastName' => $finallastname,
						'email' => $finalemail,
						'paymentMethodNonce' => $finalnonce
					));
				
					if($paypalcustomer->success){
						
						$finalpaypaltoken = $paypalcustomer->customer->paymentMethods[0]->token;
																
						$paypaltransaction = Braintree_Transaction::sale(array(
							'amount' => $finalpaymentprice,
							'paymentMethodToken' => $finalpaypaltoken, 
							'options' => array(
								'submitForSettlement' => $submitforsettlement,
								'storeInVault' => $storeinvault
							)
						));
					
						if($paypaltransaction->success){
							
							include 'functions/signature/signature.php';
							
							if($adminemail->send()){
								if($sendmobile == true){
									if($redirect == true){
										if($multilanguage == true) {
											$mobile->send($notification, $phone, $unicode);
											echo $lang['form-wait-message'];
											redirecttime($paymentsuccess2);
										} else {
											$mobile->send($notification, $phone, $unicode);
											echo $lang['form-wait-message'];
											redirecttime($paymentsuccess1);
										}
									} else {
										$mobile->send($notification, $phone, $unicode);
										echo $lang['form-success-payment-message'];
									}
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											redirecttime($paymentsuccess2);
										} else {
											echo $lang['form-wait-message'];
											redirecttime($paymentsuccess1);
										}
									} else {														
										echo $lang['form-success-payment-message'];
									}
								}
							} else {
								if($redirect == true){
									if($multilanguage == true) {
										echo $lang['form-wait-message'];
										redirecttime($mailerror2);																
									} else {
										echo $lang['form-wait-message'];
										redirecttime($mailerror1);
									}
								} else {
									echo $lang['form-mail-message'];
								}
							}
						} else {
							if($redirect == true){
								if($multilanguage == true) {
									echo $lang['form-wait-message'];
									redirecttime($transactionerror2);																
								} else {
									echo $lang['form-wait-message'];
									redirecttime($transactionerror1);
								}
							} else {
								echo $lang['form-transaction-message'];
							}										
						}
					} else {
						if($redirect == true){
							if($multilanguage == true) {
								echo $lang['form-wait-message'];
								redirecttime($customererror2);																
							} else {
								echo $lang['form-wait-message'];
								redirecttime($customererror1);
							}
						} else {
							echo $lang['form-customer-message'];
						}										
					}
					
				} else {
					
					$paypalcustomer = Braintree_Customer::create(array(
						'id' => $finalcustomerid,
						'firstName' => $finalfirstname,
						'lastName' => $finallastname,
						'email' => $finalemail,
						'paymentMethodNonce' => $finalnonce
					));
				
					if($paypalcustomer->success){
						
						$finalpaypaltoken = $paypalcustomer->customer->paymentMethods[0]->token;
																
						$paypaltransaction = Braintree_Transaction::sale(array(
							'amount' => $finalpaymentprice,
							'paymentMethodToken' => $finalpaypaltoken, 
							'options' => array(
								'submitForSettlement' => $submitforsettlement,
								'storeInVault' => $storeinvault
							)
						));
					
						if($paypaltransaction->success){
																																								
							if($adminemail->send()){
								if($sendmobile == true){
									if($redirect == true){
										if($multilanguage == true) {
											$mobile->send($notification, $phone, $unicode);
											echo $lang['form-wait-message'];
											redirecttime($paymentsuccess2);
										} else {
											$mobile->send($notification, $phone, $unicode);
											echo $lang['form-wait-message'];
											redirecttime($paymentsuccess1);
										}
									} else {
										$mobile->send($notification, $phone, $unicode);
										echo $lang['form-success-payment-message'];
									}
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											redirecttime($paymentsuccess2);
										} else {
											echo $lang['form-wait-message'];
											redirecttime($paymentsuccess1);
										}
									} else {														
										echo $lang['form-success-payment-message'];
									}
								}
							} else {
								if($redirect == true){
									if($multilanguage == true) {
										echo $lang['form-wait-message'];
										redirecttime($mailerror2);																
									} else {
										echo $lang['form-wait-message'];
										redirecttime($mailerror1);
									}
								} else {
									echo $lang['form-mail-message'];
								}
							}
						} else {
							if($redirect == true){
								if($multilanguage == true) {
									echo $lang['form-wait-message'];
									redirecttime($transactionerror2);																
								} else {
									echo $lang['form-wait-message'];
									redirecttime($transactionerror1);
								}
							} else {
								echo $lang['form-transaction-message'];
							}										
						}
					} else {
						if($redirect == true){
							if($multilanguage == true) {
								echo $lang['form-wait-message'];
								redirecttime($customererror2);																
							} else {
								echo $lang['form-wait-message'];
								redirecttime($customererror1);
							}
						} else {
							echo $lang['form-customer-message'];
						}										
					}
				}
			}
		}
	}
	
?>