<?php

  /**
   * From the Walker class. 
   *  This removes the classnames and adds accessibility tags
   */

class UW_Dropdowns_Walker_Menu extends Walker_Nav_Menu 
{

  private $CURRENT = '';

  function UW_Dropdowns_Walker_Menu() 
  {
    $menuLocations = get_nav_menu_locations();

    if ( ! wp_get_nav_menu_object( $menuLocations['primary']))
      $this->initial_dropdowns();
      
    add_filter('wp_nav_menu', array($this, 'add_role_application'));
    
	}

  function add_role_application($html) 
  {
    $newAttribs = 'class="dawgdrops-inner" ';
    $newAttribs .= 'role="application" ';
    $newAttribs .= 'aria-label="Keyboard-accessible Menu"';
    return str_replace('class="dawgdrops-inner"', $newAttribs, $html); 
  }

  function start_lvl( &$output, $depth, $args ) 
  {
    if ( $depth > 0 ) return;
		$output .= "<ul id=\"menu-{$this->CURRENT}\" ";
		$output .= "aria-expanded=\"false\" ";
		$output .= "role=\"group\" ";
    $output .= "aria-hidden=\"true\" ";
		$output .= "aria-labelledby=\"link-{$this->CURRENT}\" ";
		$output .= "class=\"dawgdrops-menu\">\n";
	}

  function end_lvl( &$output, $depth = 0, $args = array() ) 
  {
    if ( $depth > 0 )
      return;

		$indent = str_repeat("\t", $depth);
		$output .= "$indent</ul>\n";
	}

  function display_element ($element, &$children_elements, $max_depth, $depth = 0, $args, &$output)
  {
      $element->has_children = isset($children_elements[$element->ID]) && !empty($children_elements[$element->ID]);
      return parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
  }

  function start_el(&$output, $item, $depth, $args) 
  {
    if ( $depth > 1 )
      return;

    $this->CURRENT = $item->post_name;
    $title = ! empty( $item->title ) ? $item->title : $item->post_title;
    
    $caret  = $depth == 0 && $item->has_children ? '<b class="caret"></b>' : '';

		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$classes     = $depth == 0 ? array( 'dawgdrops-item', $item->classes[0] ) : array();
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );

    $li_classnames = ! empty($classes) ? 'class="'. $class_names .'"' : '';
    $li_attributes = $depth == 0 ? ' ' : '';

		$output .= $indent . '<li' . $li_attributes . $li_classnames .'>';

		//$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
    if ($depth == 0 && $item->has_children) {
  		$attributes .= ' class="dropdown-toggle"';
      $attributes .= ' id="link-'.$item->post_name.'"'; 
      $attributes .= ' aria-controls="menu-'.$item->post_name.'"'; 
      $attributes .= ' aria-haspopup="true"'; 
      $attributes .= ' aria-expanded="false" '; 		  		
    }
    else if ($depth == 1) { 
      // $attributes .= ' tabindex="-1"';
    }

		$item_output = $args->before;
		$item_output .= '<a'. $attributes .'>';
		$item_output .= $args->link_before . apply_filters( 'the_title', $title, $item->ID ) . $args->link_after;
		$item_output .= $caret;
		$item_output .= '</a>';
		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}


  function initial_dropdowns()
  {

    $pages = get_pages('number=1');
    $page = $pages[0];

    echo '<div class="dawgdrops-inner">
      <ul id="menu-dropdowns" class="dawgdrops-nav">
        <li class="dawgdrops-item">
        <a href="'. get_permalink( $page->ID ) .'" class="dropdown-toggle" title="'. $page->post_title .'">' . $page->post_title . '</a>
        </li>
      </ul>
    </div>';
  }
}
