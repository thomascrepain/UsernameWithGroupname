<?php
/**
 * Html form for account creation
 *
 * @file
 * @ingroup Templates
 */

/**
 * @defgroup Templates Templates
 */

if( !defined( 'MEDIAWIKI' ) ) die( -1 );

/**
 * @ingroup Templates
 */
class UsernameWithGroupnameUsercreateTemplate extends QuickTemplate {
	function addInputItem( $name, $value, $type, $msg, $helptext = false ) {
		$this->data['extraInput'][] = array(
			'name' => $name,
			'value' => $value,
			'type' => $type,
			'msg' => $msg,
			'helptext' => $helptext,
		);
	}

	function execute() {
		if( $this->data['message'] ) {
?>
	<div class="<?php $this->text('messagetype') ?>box">
		<?php if ( $this->data['messagetype'] == 'error' ) { ?>
			<strong><?php $this->msg( 'loginerror' )?></strong><br />
		<?php } ?>
		<?php $this->html('message') ?>
	</div>
	<div class="visualClear"></div>
<?php } ?>

<div id="signupstart"><?php $this->msgWiki( 'signupstart' ); ?></div>
<div id="userlogin">

<form name="userlogin2" id="userlogin2" method="post" action="<?php $this->text('action') ?>">
	<h2><?php $this->msg('createaccount') ?></h2>
	<p id="userloginlink"><?php $this->html('link') ?></p>
	<?php $this->html('header'); /* pre-table point for form plugins... */ ?>
	<?php if( $this->haveData( 'languages' ) ) { ?><div id="languagelinks"><p><?php $this->html( 'languages' ); ?></p></div><?php } ?>
	<table>
		<tr>
			<td class="mw-label"><label for='wpName2'><?php $this->msg('yourname') ?></label></td>
			<td class="mw-input">
				<?php
			echo Html::input( 'wpName', '', 'text', array(
				'class' => 'loginText',
				'id' => 'wpName2',
				'tabindex' => '1',
				'size' => '20',
				'required',
				'autofocus'
			) ); ?>
			</td>
		</tr>
		<tr>
			<td class="mw-label"><label for='wpPassword2'><?php $this->msg('yourpassword') ?></label></td>
			<td class="mw-input">
<?php
			echo Html::input( 'wpPassword', null, 'password', array(
				'class' => 'loginPassword',
				'id' => 'wpPassword2',
				'tabindex' => '2',
				'size' => '20'
			) + User::passwordChangeInputAttribs() ); ?>
			</td>
		</tr>
	<?php if( $this->data['usedomain'] ) {
		$doms = "";
		foreach( $this->data['domainnames'] as $dom ) {
			$doms .= "<option>" . htmlspecialchars( $dom ) . "</option>";
		}
	?>
		<tr>
			<td class="mw-label"><?php $this->msg( 'yourdomainname' ) ?></td>
			<td class="mw-input">
				<select name="wpDomain" value="<?php $this->text( 'domain' ) ?>"
					tabindex="3">
					<?php echo $doms ?>
				</select>
			</td>
		</tr>
	<?php } ?>
		<tr>
			<td class="mw-label"><label for='wpRetype'><?php $this->msg('yourpasswordagain') ?></label></td>
			<td class="mw-input">
				<?php
		echo Html::input( 'wpRetype', null, 'password', array(
			'class' => 'loginPassword',
			'id' => 'wpRetype',
			'tabindex' => '4',
			'size' => '20'
		) + User::passwordChangeInputAttribs() ); ?>
			</td>
		</tr>
		<tr>
		    <td class="mw-label"><label for='wpUsergroup'><?php $this->msg('yourgroupname') ?></label></td>
		    <td class="mw-input">
			<select class="loginText" name="wpUsergroup" tabindex="5">
			    <?php
			    	global $wgUsergroups;
			    	foreach ($wgUsergroups as $optionValue => $textValue) {
				?>
			    <option value="<?=$optionValue?>"><?=$textValue?></option>
			    <?php } ?>
			</select>
		    </td>
		</tr>
		<tr>
			<?php if( $this->data['useemail'] ) { ?>
				<td class="mw-label"><label for='wpEmail'><?php $this->msg('youremail') ?></label></td>
				<td class="mw-input">
					<?php
		echo Html::input( 'wpEmail', $this->data['email'], 'email', array(
			'class' => 'loginText',
			'id' => 'wpEmail',
			'tabindex' => '5',
			'size' => '20'
		) ); ?>
					<div class="prefsectiontip">
						<?php  // duplicated in Preferences.php profilePreferences()
							if( $this->data['emailrequired'] ) {
								$this->msgWiki('prefs-help-email-required');
							} else {
								$this->msgWiki('prefs-help-email');
							}
							if( $this->data['emailothers'] ) {
								$this->msgWiki('prefs-help-email-others');
							} ?>
					</div>
				</td>
			<?php } ?>
			<?php if( $this->data['userealname'] ) { ?>
				</tr>
				<tr>
					<td class="mw-label"><label for='wpRealName'><?php $this->msg('yourrealname') ?></label></td>
					<td class="mw-input">
						<input type='text' class='loginText' name="wpRealName" id="wpRealName"
							tabindex="6"
							value="<?php $this->text('realname') ?>" size='20' />
						<div class="prefsectiontip">
							<?php $this->msgWiki('prefs-help-realname'); ?>
						</div>
					</td>
			<?php } ?>
			<?php if( $this->data['usereason'] ) { ?>
				</tr>
				<tr>
					<td class="mw-label"><label for='wpReason'><?php $this->msg('createaccountreason') ?></label></td>
					<td class="mw-input">
						<input type='text' class='loginText' name="wpReason" id="wpReason"
							tabindex="7"
							value="<?php $this->text('reason') ?>" size='20' />
					</td>
			<?php } ?>
		</tr>
		<?php if( $this->data['canremember'] ) { ?>
		<tr>
			<td></td>
			<td class="mw-input">
				<?php
				global $wgCookieExpiration, $wgLang;
				echo Xml::checkLabel(
					wfMsgExt( 'remembermypassword', 'parsemag', $wgLang->formatNum( ceil( $wgCookieExpiration / ( 3600 * 24 ) ) ) ),
					'wpRemember',
					'wpRemember',
					$this->data['remember'],
					array( 'tabindex' => '8' )
				)
				?>
			</td>
		</tr>
<?php   }

		$tabIndex = 9;
		if ( isset( $this->data['extraInput'] ) && is_array( $this->data['extraInput'] ) ) {
			foreach ( $this->data['extraInput'] as $inputItem ) { ?>
		<tr>
			<?php
				if ( !empty( $inputItem['msg'] ) && $inputItem['type'] != 'checkbox' ) {
					?><td class="mw-label"><label for="<?php
					echo htmlspecialchars( $inputItem['name'] ); ?>"><?php
					$this->msgWiki( $inputItem['msg'] ) ?></label><?php
				} else {
					?><td><?php
				}
			?></td>
			<td class="mw-input">
				<input type="<?php echo htmlspecialchars( $inputItem['type'] ) ?>" name="<?php
				echo htmlspecialchars( $inputItem['name'] ); ?>"
					tabindex="<?php echo $tabIndex++; ?>"
					value="<?php
				if ( $inputItem['type'] != 'checkbox' ) {
					echo htmlspecialchars( $inputItem['value'] );
				} else {
					echo '1';
				}
					?>" id="<?php echo htmlspecialchars( $inputItem['name'] ); ?>"
					<?php
				if ( $inputItem['type'] == 'checkbox' && !empty( $inputItem['value'] ) )
					echo 'checked="checked"';
					?> /> <?php
					if ( $inputItem['type'] == 'checkbox' && !empty( $inputItem['msg'] ) ) {
						?>
				<label for="<?php echo htmlspecialchars( $inputItem['name'] ); ?>"><?php
					$this->msgHtml( $inputItem['msg'] ) ?></label><?php
					}
				if( $inputItem['helptext'] !== false ) {
				?>
				<div class="prefsectiontip">
					<?php $this->msgWiki( $inputItem['helptext'] ); ?>
				</div>
				<?php } ?>
			</td>
		</tr>
<?php

			}
		}
?>
		<tr>
			<td></td>
			<td class="mw-submit">
				<input type='submit' name="wpCreateaccount" id="wpCreateaccount"
					tabindex="<?php echo $tabIndex++; ?>"
					value="<?php $this->msg('createaccount') ?>" />
				<?php if( $this->data['createemail'] ) { ?>
				<input type='submit' name="wpCreateaccountMail" id="wpCreateaccountMail"
					tabindex="<?php echo $tabIndex++; ?>"
					value="<?php $this->msg('createaccountmail') ?>" />
				<?php } ?>
			</td>
		</tr>
	</table>
<?php if( $this->haveData( 'uselang' ) ) { ?><input type="hidden" name="uselang" value="<?php $this->text( 'uselang' ); ?>" /><?php } ?>
<?php if( $this->haveData( 'token' ) ) { ?><input type="hidden" name="wpCreateaccountToken" value="<?php $this->text( 'token' ); ?>" /><?php } ?>
</form>
</div>
<div id="signupend"><?php $this->html( 'signupend' ); ?></div>
<?php

	}
}
