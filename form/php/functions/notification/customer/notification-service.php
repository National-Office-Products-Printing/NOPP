<?php
							
	if($sendcopytome == true){
										
		if($finalsendtome === $lang['form-send-to-me-option-1']){
			
			if($automessage == true){
	
				if ($mysql == true){
										
					if($adminemail->send()){
					
						include 'messages/customer/automessage-service.php';
						include 'automessage.php';
					
						if($automail->send()){
					
							include 'messages/customer/message-copy-service.php';
							include 'emailcopy.php';
							
							if($emailcopy->send()){
								if($sendmobile == true){
									if($redirect == true){
										if($multilanguage == true) {
											$mobile->send($notification, $phone, $unicode);
											echo $lang['form-wait-message'];
											db_insert_service($tablenameservice,$insertservicevalues);	
											redirecttime($servicesuccess2);															
										} else {
											$mobile->send($notification, $phone, $unicode);
											echo $lang['form-wait-message'];
											db_insert_service($tablenameservice,$insertservicevalues);	
											redirecttime($servicesuccess1);
										}
									} else {
										$mobile->send($notification, $phone, $unicode);
										db_insert_service($tablenameservice,$insertservicevalues);
										echo $lang['form-success-service-message'];
									}
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											db_insert_service($tablenameservice,$insertservicevalues);	
											redirecttime($servicesuccess2);
										} else {
											echo $lang['form-wait-message'];
											db_insert_service($tablenameservice,$insertservicevalues);	
											redirecttime($servicesuccess1);
										}
									} else {														
										db_insert_service($tablenameservice,$insertservicevalues);	
										echo $lang['form-success-service-message'];
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
														
					if($adminemail->send()){
					
						include 'messages/customer/automessage-service.php';
						include 'automessage.php';
					
						if($automail->send()){
					
							include 'messages/customer/message-copy-service.php';
							include 'emailcopy.php';
							
							if($emailcopy->send()){
								if($sendmobile == true){
									if($redirect == true){
										if($multilanguage == true) {
											$mobile->send($notification, $phone, $unicode);
											echo $lang['form-wait-message'];
											redirecttime($servicesuccess2);															
										} else {
											$mobile->send($notification, $phone, $unicode);
											echo $lang['form-wait-message'];
											redirecttime($servicesuccess1);
										}
									} else {
										$mobile->send($notification, $phone, $unicode);
										echo $lang['form-success-service-message'];
									}
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											redirecttime($servicesuccess2);
										} else {
											echo $lang['form-wait-message'];
											redirecttime($servicesuccess1);
										}
									} else {														
										echo $lang['form-success-service-message'];
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
				}
				
			} else {
	
				if ($mysql == true){
										
					if($adminemail->send()){
					
						include 'messages/customer/message-copy-service.php';
						include 'emailcopy.php';
						
						if($emailcopy->send()){
							if($sendmobile == true){
								if($redirect == true){
									if($multilanguage == true) {
										$mobile->send($notification, $phone, $unicode);
										echo $lang['form-wait-message'];
										db_insert_service($tablenameservice,$insertservicevalues);	
										redirecttime($servicesuccess2);															
									} else {
										$mobile->send($notification, $phone, $unicode);
										echo $lang['form-wait-message'];
										db_insert_service($tablenameservice,$insertservicevalues);	
										redirecttime($servicesuccess1);
									}
								} else {
									$mobile->send($notification, $phone, $unicode);
									db_insert_service($tablenameservice,$insertservicevalues);
									echo $lang['form-success-service-message'];
								}
							} else {
								if($redirect == true){
									if($multilanguage == true) {
										echo $lang['form-wait-message'];
										db_insert_service($tablenameservice,$insertservicevalues);	
										redirecttime($servicesuccess2);
									} else {
										echo $lang['form-wait-message'];
										db_insert_service($tablenameservice,$insertservicevalues);	
										redirecttime($servicesuccess1);
									}
								} else {														
									db_insert_service($tablenameservice,$insertservicevalues);	
									echo $lang['form-success-service-message'];
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
														
					if($adminemail->send()){
					
						include 'messages/customer/message-copy-service.php';
						include 'emailcopy.php';
						
						if($emailcopy->send()){
							if($sendmobile == true){
								if($redirect == true){
									if($multilanguage == true) {
										$mobile->send($notification, $phone, $unicode);
										echo $lang['form-wait-message'];
										redirecttime($servicesuccess2);															
									} else {
										$mobile->send($notification, $phone, $unicode);
										echo $lang['form-wait-message'];
										redirecttime($servicesuccess1);
									}
								} else {
									$mobile->send($notification, $phone, $unicode);
									echo $lang['form-success-service-message'];
								}
							} else {
								if($redirect == true){
									if($multilanguage == true) {
										echo $lang['form-wait-message'];
										redirecttime($servicesuccess2);
									} else {
										echo $lang['form-wait-message'];
										redirecttime($servicesuccess1);
									}
								} else {														
									echo $lang['form-success-service-message'];
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
				}
			}
			
		} else {
			
			if($automessage == true){
	
				if ($mysql == true){
							
					if($adminemail->send()){
					
						include 'messages/customer/automessage-service.php';
						include 'automessage.php';
					
						if($automail->send()){
							if($sendmobile == true){
								if($redirect == true){
									if($multilanguage == true) {
										$mobile->send($notification, $phone, $unicode);
										echo $lang['form-wait-message'];
										db_insert_service($tablenameservice,$insertservicevalues);	
										redirecttime($servicesuccess2);															
									} else {
										$mobile->send($notification, $phone, $unicode);
										echo $lang['form-wait-message'];
										db_insert_service($tablenameservice,$insertservicevalues);	
										redirecttime($servicesuccess1);
									}
								} else {
									$mobile->send($notification, $phone, $unicode);
									db_insert_service($tablenameservice,$insertservicevalues);
									echo $lang['form-success-service-message'];
								}
							} else {
								if($redirect == true){
									if($multilanguage == true) {
										echo $lang['form-wait-message'];
										db_insert_service($tablenameservice,$insertservicevalues);	
										redirecttime($servicesuccess2);
									} else {
										echo $lang['form-wait-message'];
										db_insert_service($tablenameservice,$insertservicevalues);	
										redirecttime($servicesuccess1);
									}
								} else {														
									db_insert_service($tablenameservice,$insertservicevalues);	
									echo $lang['form-success-service-message'];
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
														
					if($adminemail->send()){
					
						include 'messages/customer/automessage-service.php';
						include 'automessage.php';
					
						if($automail->send()){
							if($sendmobile == true){
								if($redirect == true){
									if($multilanguage == true) {
										$mobile->send($notification, $phone, $unicode);
										echo $lang['form-wait-message'];
										redirecttime($servicesuccess2);															
									} else {
										$mobile->send($notification, $phone, $unicode);
										echo $lang['form-wait-message'];
										redirecttime($servicesuccess1);
									}
								} else {
									$mobile->send($notification, $phone, $unicode);
									echo $lang['form-success-service-message'];
								}
							} else {
								if($redirect == true){
									if($multilanguage == true) {
										echo $lang['form-wait-message'];
										redirecttime($servicesuccess2);
									} else {
										echo $lang['form-wait-message'];
										redirecttime($servicesuccess1);
									}
								} else {														
									echo $lang['form-success-service-message'];
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
				}
				
			} else {
	
				if ($mysql == true){
									
					if($adminemail->send()){
						if($sendmobile == true){
							if($redirect == true){
								if($multilanguage == true) {
									$mobile->send($notification, $phone, $unicode);
									echo $lang['form-wait-message'];
									db_insert_service($tablenameservice,$insertservicevalues);	
									redirecttime($servicesuccess2);															
								} else {
									$mobile->send($notification, $phone, $unicode);
									echo $lang['form-wait-message'];
									db_insert_service($tablenameservice,$insertservicevalues);	
									redirecttime($servicesuccess1);
								}
							} else {
								$mobile->send($notification, $phone, $unicode);
								db_insert_service($tablenameservice,$insertservicevalues);
								echo $lang['form-success-service-message'];
							}
						} else {
							if($redirect == true){
								if($multilanguage == true) {
									echo $lang['form-wait-message'];
									db_insert_service($tablenameservice,$insertservicevalues);	
									redirecttime($servicesuccess2);
								} else {
									echo $lang['form-wait-message'];
									db_insert_service($tablenameservice,$insertservicevalues);	
									redirecttime($servicesuccess1);
								}
							} else {														
								db_insert_service($tablenameservice,$insertservicevalues);	
								echo $lang['form-success-service-message'];
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
														
					if($adminemail->send()){
						if($sendmobile == true){
							if($redirect == true){
								if($multilanguage == true) {
									$mobile->send($notification, $phone, $unicode);
									echo $lang['form-wait-message'];
									redirecttime($servicesuccess2);															
								} else {
									$mobile->send($notification, $phone, $unicode);
									echo $lang['form-wait-message'];
									redirecttime($servicesuccess1);
								}
							} else {
								$mobile->send($notification, $phone, $unicode);
								echo $lang['form-success-service-message'];
							}
						} else {
							if($redirect == true){
								if($multilanguage == true) {
									echo $lang['form-wait-message'];
									redirecttime($servicesuccess2);
								} else {
									echo $lang['form-wait-message'];
									redirecttime($servicesuccess1);
								}
							} else {														
								echo $lang['form-success-service-message'];
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
				}
			}
		}
		
	} else {
		
		if($automessage == true){

			if ($mysql == true){
							
				if($adminemail->send()){
				
					include 'messages/customer/automessage-service.php';
					include 'automessage.php';
				
					if($automail->send()){
						if($sendmobile == true){
							if($redirect == true){
								if($multilanguage == true) {
									$mobile->send($notification, $phone, $unicode);
									echo $lang['form-wait-message'];
									db_insert_service($tablenameservice,$insertservicevalues);	
									redirecttime($servicesuccess2);															
								} else {
									$mobile->send($notification, $phone, $unicode);
									echo $lang['form-wait-message'];
									db_insert_service($tablenameservice,$insertservicevalues);	
									redirecttime($servicesuccess1);
								}
							} else {
								$mobile->send($notification, $phone, $unicode);
								db_insert_service($tablenameservice,$insertservicevalues);
								echo $lang['form-success-service-message'];
							}
						} else {
							if($redirect == true){
								if($multilanguage == true) {
									echo $lang['form-wait-message'];
									db_insert_service($tablenameservice,$insertservicevalues);	
									redirecttime($servicesuccess2);
								} else {
									echo $lang['form-wait-message'];
									db_insert_service($tablenameservice,$insertservicevalues);	
									redirecttime($servicesuccess1);
								}
							} else {														
								db_insert_service($tablenameservice,$insertservicevalues);	
								echo $lang['form-success-service-message'];
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
												
				if($adminemail->send()){
				
					include 'messages/customer/automessage-service.php';
					include 'automessage.php';
				
					if($automail->send()){
						if($sendmobile == true){
							if($redirect == true){
								if($multilanguage == true) {
									$mobile->send($notification, $phone, $unicode);
									echo $lang['form-wait-message'];
									redirecttime($servicesuccess2);															
								} else {
									$mobile->send($notification, $phone, $unicode);
									echo $lang['form-wait-message'];
									redirecttime($servicesuccess1);
								}
							} else {
								$mobile->send($notification, $phone, $unicode);
								echo $lang['form-success-service-message'];
							}
						} else {
							if($redirect == true){
								if($multilanguage == true) {
									echo $lang['form-wait-message'];
									redirecttime($servicesuccess2);
								} else {
									echo $lang['form-wait-message'];
									redirecttime($servicesuccess1);
								}
							} else {														
								echo $lang['form-success-service-message'];
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
			}
			
		} else {

			if ($mysql == true){
								
				if($adminemail->send()){
					if($sendmobile == true){
						if($redirect == true){
							if($multilanguage == true) {
								$mobile->send($notification, $phone, $unicode);
								echo $lang['form-wait-message'];
								db_insert_service($tablenameservice,$insertservicevalues);	
								redirecttime($servicesuccess2);															
							} else {
								$mobile->send($notification, $phone, $unicode);
								echo $lang['form-wait-message'];
								db_insert_service($tablenameservice,$insertservicevalues);	
								redirecttime($servicesuccess1);
							}
						} else {
							$mobile->send($notification, $phone, $unicode);
							db_insert_service($tablenameservice,$insertservicevalues);
							echo $lang['form-success-service-message'];
						}
					} else {
						if($redirect == true){
							if($multilanguage == true) {
								echo $lang['form-wait-message'];
								db_insert_service($tablenameservice,$insertservicevalues);	
								redirecttime($servicesuccess2);
							} else {
								echo $lang['form-wait-message'];
								db_insert_service($tablenameservice,$insertservicevalues);	
								redirecttime($servicesuccess1);
							}
						} else {														
							db_insert_service($tablenameservice,$insertservicevalues);	
							echo $lang['form-success-service-message'];
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
											
				if($adminemail->send()){
					if($sendmobile == true){
						if($redirect == true){
							if($multilanguage == true) {
								$mobile->send($notification, $phone, $unicode);
								echo $lang['form-wait-message'];
								redirecttime($servicesuccess2);															
							} else {
								$mobile->send($notification, $phone, $unicode);
								echo $lang['form-wait-message'];
								redirecttime($servicesuccess1);
							}
						} else {
							$mobile->send($notification, $phone, $unicode);
							echo $lang['form-success-service-message'];
						}
					} else {
						if($redirect == true){
							if($multilanguage == true) {
								echo $lang['form-wait-message'];
								redirecttime($servicesuccess2);
							} else {
								echo $lang['form-wait-message'];
								redirecttime($servicesuccess1);
							}
						} else {														
							echo $lang['form-success-service-message'];
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
			}
		}
	}
	
?>