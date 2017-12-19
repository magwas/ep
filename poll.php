<?php


class ElektoriParlament_Vote {
	const FORM_HEADER = <<<'EOT'
	<div class="ep_vote">
	<form method="post"
	      action="http://civs.cs.cornell.edu/cgi-bin/vote.pl"
	      enctype="multipart/form-data"
	      name="CastVote">
	    <input type="hidden" name="id" value="%s"  />
	    <table class="ep_vote_form" id="ballot" border="0" cellpadding="5" cellspacing="0"><tr><td>
	        <table class="ep_vote_alternatives" cellpadding="5px" cellspacing="0" border="1" id="preftable">
	           <tr class="heading">
		       <th>&nbsp;Választás&nbsp;</th><th>Rang</th></tr>
EOT;
	/// id=<post id>
	
	
	const ALTERNATIVE_ROW = <<<'EOT'
            <tr class="ep_vote_alternative_row" onclick="select_row(this, event.shiftKey||event.ctrlKey);">
                <td class="choice">%s</td>
                <td class="choice_rank"><select size="1" name="%s" onchange="sort_rows()">
%s
                </select></td>
            </tr>
EOT;
	// label="egyik sem", slug="C2", selectoptions
	//                  <option value="1" label="1" >1</option>
	//                  <option value="2" label="2" >2</option>
	//                  <option value="3" label="3"  selected="selected">3</option>
	
	
	const FORM_FOOTER = <<<'EOT'
	        </table></td>
	<td rowspan="2" width="0" valign="top" align="left">
		    <input type="button" class="move_but" id="move_top" disabled="disabled" value="tetejére"
	                onclick="do_move_top()" /><br />
		    <input type="button" class="move_but" id="move_up" disabled="disabled" value="felfelé"
	                onclick="do_move_up()" /><br />
		    <input type="button" class="move_but" id="make_tie" disabled="disabled" value="legyen döntetlen"
	                onclick="do_make_tie()" /><br />
		    <input type="button" class="move_but" id="move_down" disabled="disabled" value="lefelé"
	                onclick="do_move_down()" /><br />
		    <input type="button" class="move_but" id="move_bottom" disabled="disabled" value="aljára"
	                onclick="do_move_bottom()" />
		    <table class="form"><tr><td>
		    <p id="jsnohelp">Ezek a gombok nem aktívak, mert a böngésződ nem támogatja a javascriptet.</p>
		    <div style="display: none" id="jshelp">Rangsorold a lehetőségeket az alábbi módok valamelyikével:
	    <ol>
	        <li>húzd a sort a helyére
	        <li>Használd a rangsor oszlopban a legördülő menüt
	        <li>válassz ki sorokat és használd a fenti gombokat
	    </ol></div>
		    </td></tr></table>
		    </td>
	</tr>
	<tr><td style="height: 100%"><input id="vote" type="submit" value="Rangsor megadása" name="Vote" /></td></tr>
	</table>
	</form>
	</div>
EOT;
	// no params
	
	function vote_shortcode( ) {
		global $post;
	        if( ! is_feed() ) {
			$form = sprintf(self::FORM_HEADER, get_the_id($post));
			$kids = ElektoriParlament::get_child_by_taxonomy($post, 'javaslat', 'vita');
			while($kids->have_posts()) {
				$kids->the_post();
				$form .= sprintf(self::ALTERNATIVE_ROW, get_the_title(), $post->post_name,'AAA');
			}
			wp_reset_postdata();
			$count=$kids->post_count;
			$form .= self::FORM_FOOTER;
	                return $form;
	        } else {
	                return __( 'Figyelem: A problémafelvetésben szavazás van. Látogasson el az oldalra!', 'ep' );
	        }
	}
	
}

add_shortcode( 'vote', Array('ElektoriParlament_Vote', 'vote_shortcode') );
?>
