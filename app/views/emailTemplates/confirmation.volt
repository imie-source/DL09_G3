

<tbody>
	<tr>
		<td style="padding:40px 0  0 0;">
			<p style="color:#000;font-size: 16px;line-height:24px;font-family:'HelveticaNeue','Helvetica Neue',Helvetica,Arial,sans-serif;font-weight:normal;">

				<h2 style="font-size: 14px;font-family:'HelveticaNeue','Helvetica Neue',Helvetica,Arial,sans-serif;">Vous y êtes presque!!! Il ne vous reste plus qu'à confirmer votre email.</h2>

				<p style="font-size: 13px;line-height:24px;font-family:'HelveticaNeue','Helvetica Neue',Helvetica,Arial,sans-serif;">Vous avez bien créé un nouveau compte sur <a href="{{ publicUrl }}" style="text-decoration: none;"><?php echo $this->config->application->siteTitle; ?></a>. Pour l'activer, veuillez cliquer sur le lien ci dessous.

				<br>
				<br>
				<a style="background:#438EB9;color:#fff;padding:10px;text-decoration:none;" href="http://{{ publicUrl }}{{ confirmUrl }}">J'active mon compte</a>

				<br>
				<br>
				A très vite sur <a href="{{ publicUrl }}" style="text-decoration: none;"><?php echo $this->config->application->siteTitle; ?></a>!
				<br>
			</p>
		</td>
	</tr>
	<tr>
		<p style="color:#000;font-size: 10px;line-height:24px;font-family:'HelveticaNeue','Helvetica Neue',Helvetica,Arial,sans-serif;font-weight:normal;">
			Cette email a été généré automatiquement. N'y répondez pas! Aucune demande ne sera traitée sur cette adresse. Pour nous contacter, veuillez passer par la partie Contact du site <a href="{{ publicUrl }}" style="text-decoration: none;"><?php echo $this->config->application->siteTitle; ?></a>. Vous pouvez à tout moment désactiver les notifications par emails dans votre profile.
		</p>
	</tr>
</tbody>

