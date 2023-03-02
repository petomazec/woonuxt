<?php
if ( ! defined( 'ABSPATH' ) ) exit;
global  $wpdb ;
$result = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpaicg_log", OBJECT );
$reversedArray = array_reverse( $result );
?>

<div class="wrap">
    <h1>GPT3 Logs</h1>
    <div class="table-responsive">
        <table class="table">
            <thead>
            <tr>
                <td scope="col">ID</td>
                <td scope="col">Title</td>
                <td scope="col">Date</td>
                <td scope="col">Action</td>
            </tr>
            </thead>
            <tbody>
            <?php
            $counter = 1;
            foreach ( $reversedArray as $results ) {
                ?>
                <tr>
                    <td><?php
                        echo  esc_html( $counter ) ;
                        ?></td>
                    <td><a href="<?php
                        echo  get_edit_post_link( $results->post_id ) ;
                        ?>"><?php
                            echo  esc_html($results->title) ;
                            ?></a></td>
                    <td><?php
                        echo  esc_html($results->added_date) ;
                        ?></td>
                    <td>
                        <a href="<?php
                        echo  get_edit_post_link( $results->post_id ) ;
                        ?>">
                            <span class="dashicons dashicons-edit-page"></span>
                        </a>
                    </td>
                </tr>
                <?php
                $counter++;
            }
            ?>
            </tbody>
        </table>
    </div>

</div>
